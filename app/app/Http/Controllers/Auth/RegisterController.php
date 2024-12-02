<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

final class RegisterController extends Controller
{
    public function __invoke()
    {
        $data = request()->validate([
            'name'     => ['required', 'min:3', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:40'],
        ]);

        User::create($data);

        return response()->json([
            'message' => 'User registered successfully',
        ]);
    }
}
