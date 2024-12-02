<?php

use App\Enum\RequestScrapingStatus;
use App\Jobs\RequestScrapingJob;
use App\Models\{Opportunity, RequestScraping, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;

describe('RequestScrapingTest', function () {
    it('should make one request and return status,query-params and hash', function () {

        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('scraping.request'), [
            'query' => 'laravel',
        ]);

        assertDatabaseHas('request_scrapings', [
            'query'   => 'laravel',
            'user_id' => auth()->id(),
            'hash'    => $response->json('uuid_query'),
            'status'  => RequestScrapingStatus::PENDING,
        ]);

        Queue::fake();

        dispatch(new RequestScrapingJob([
            'hash'  => $response->json('uuid_query'),
            'query' => 'laravel',
        ]))->onQueue('scraping');

        Queue::assertPushedOn('scraping', RequestScrapingJob::class, function ($job) use ($response) {
            return $job->data['hash'] === $response->json('uuid_query');
        });

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'query_params',
                'uuid_query',
            ]);
    });

    it('should return error if hash is not found', function () {

        Sanctum::actingAs(User::factory()->create());

        $response = $this->get(route('scraping.find', ['hash' => '943u943jdsds']));

        $response->assertStatus(404);
    });

    it('should return error if hash is found but status is pending', function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $f = RequestScraping::factory()->for($user)->create([
            'query' => 'laravel',
        ]);

        $response = $this->get(route('scraping.find', [
            'hash' => $f->hash,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status'  => 'PENDING',
                'hash'    => $f->hash,
                'message' => 'Please wait a few minutes, the scraping is still in progress.',
            ]);
    });

    it('should make on request and return query with results', function () {

        $user = User::factory()->create();

        $opportunities = Opportunity::factory()->count(10)->create();

        Sanctum::actingAs($user);

        $f = RequestScraping::factory()->create([
            'query'   => 'laravel',
            'user_id' => $user->id,
        ]);

        $f->opportunities()->syncWithoutDetaching($opportunities);

        $f->update(['status' => RequestScrapingStatus::DONE]);

        $response = $this->get(route('scraping.find', ['hash' => $f->hash]));

        $response->assertJsonStructure([
            'status',
            'hash',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'url',
                    'details',
                    'business',
                    'status',
                    'created_at',
                ],
            ],
            'pagination' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ]);

        $response->assertJsonFragment(['status' => 'DONE']);

        $response->assertJsonCount(10, 'data');

        $response->assertJsonFragment([
            'id'    => $opportunities[0]->id,
            'title' => $opportunities[0]->title,
        ]);
    });

});
