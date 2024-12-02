<?php

use App\Models\User;

use function Pest\Laravel\postJson;

test('should make login user in the application', function () {

    User::factory()->create([
        'email'    => 'john@doe.com',
        'password' => 'password',
    ]);

    $request = postJson(route('login'), [
        'email'    => 'john@doe.com',
        'password' => 'password',

    ])->assertOk();

    $request->assertJsonStructure(['access_token']);
});
