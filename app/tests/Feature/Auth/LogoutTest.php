<?php

use App\Models\User;

use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;

test('should make logout user in the application', function () {

    Sanctum::actingAs(User::factory()->create());

    postJson(route('logout'), [])->assertNoContent();
});
