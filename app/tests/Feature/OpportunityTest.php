<?php

use App\Enum\OpportunityStatus;
use App\Models\{Opportunity, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertSoftDeleted, delete, get, patch, post};

describe('it should test methods on OpportunityController', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();

        $this->opportunity = Opportunity::factory()->for($this->user)->create();

        Sanctum::actingAs($this->user);
    });

    it("should't return a list of opportunities", function () {

        $response = get('opportunity');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [],
        ]);
    });

    it('should return a list of opportunities with status ACCEPTED', function () {

        $this->opportunity->status = OpportunityStatus::ACCEPTED;

        $this->opportunity->save();

        $response = get('opportunity');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'id'         => $this->opportunity->id,
                    'title'      => $this->opportunity->title,
                    'url'        => $this->opportunity->url,
                    'details'    => $this->opportunity->details,
                    'business'   => $this->opportunity->business,
                    'status'     => strtoupper($this->opportunity->status->value),
                    'created_at' => $this->opportunity->created_at->format('Y-m-d H:i:s'),
                ],
            ],
        ]);

    });

    it('should return more details the opportunity', function () {

        $response = get('opportunity/1');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'id'         => $this->opportunity->id,
                'title'      => $this->opportunity->title,
                'url'        => $this->opportunity->url,
                'details'    => $this->opportunity->details,
                'business'   => $this->opportunity->business,
                'created_at' => $this->opportunity->created_at,
                'status'     => strtoupper($this->opportunity->status->value),
            ],
        ]);
    });

    it('should create one opportunity from user', function () {

        $response = post('opportunity', [
            'user_id'  => $this->user->id,
            'title'    => 'Job Title',
            'url'      => 'https://job.com',
            'details'  => 'Job Details',
            'business' => 'Job Business',
        ]);

        $response->assertStatus(201);

        assertDatabaseHas('opportunities', [
            'user_id'  => $this->user->id,
            'title'    => 'Job Title',
            'url'      => 'https://job.com',
            'details'  => 'Job Details',
            'business' => 'Job Business',
            'status'   => OpportunityStatus::PENDING,
        ]);

        $response->assertJson([
            'data' => [
                'title'      => 'Job Title',
                'url'        => 'https://job.com',
                'details'    => 'Job Details',
                'business'   => 'Job Business',
                'created_at' => $this->opportunity->created_at,
                'status'     => strtoupper($this->opportunity->status->value),
            ],
        ]);
    });

    it('user should can updated one opportunity if him is own', function () {

        $this->opportunity->status = OpportunityStatus::ACCEPTED;
        $this->opportunity->save();

        $response = patch('opportunity/1', [
            'title'    => 'Job Title 2',
            'url'      => 'https://job2.com',
            'details'  => 'Job Details 2',
            'business' => 'Job Business 2',
        ]);

        $response->assertStatus(200);

        assertDatabaseHas('opportunities', [
            'id'       => 1,
            'title'    => 'Job Title 2',
            'url'      => 'https://job2.com',
            'details'  => 'Job Details 2',
            'business' => 'Job Business 2',
        ]);

        $response->assertJson([
            'data' => [
                'title'      => 'Job Title 2',
                'url'        => 'https://job2.com',
                'details'    => 'Job Details 2',
                'business'   => 'Job Business 2',
                'created_at' => $this->opportunity->created_at,
                'status'     => strtoupper($this->opportunity->status->value),
            ],
        ]);
    });

    it("user not should can updated one opportunity if him isn't own", function () {

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = patch('opportunity/1', [
            'title'    => 'Job Title 2',
            'url'      => 'https://job2.com',
            'details'  => 'Job Details 2',
            'business' => 'Job Business 2',
        ]);

        $response->assertStatus(403);
    });

    it('user not should can updated one opportunity if status is PENDING', function () {

        $this->opportunity->status = OpportunityStatus::PENDING;

        $this->opportunity->save();

        $response = patch('opportunity/1', [
            'title'    => 'Job Title 2',
            'url'      => 'https://job2.com',
            'details'  => 'Job Details 2',
            'business' => 'Job Business 2',
        ]);

        $response->assertStatus(403);
    });

    it('user should can delete one opportunity if him is own', function () {

        $response = delete('opportunity/1');

        $response->assertStatus(204);

        assertSoftDeleted('opportunities', [
            'id' => 1,
        ]);
    });
});
