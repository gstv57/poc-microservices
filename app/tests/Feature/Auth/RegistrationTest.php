<?php

use App\Models\User;

use function Pest\Laravel\{assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

test('should be able to register in the application', function () {
    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'john@doe.com',
        'password' => 'password',

    ])->assertOk();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john@doe.com',
    ]);

    $joeDoe = User::whereEmail('john@doe.com')->first();

    assertTrue(
        Hash::check('password', $joeDoe->password),
    );
});
