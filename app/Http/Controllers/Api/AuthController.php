<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if (!auth()->attempt($request->only('email', 'password'))) {
            return $this->error('Credentials do not match', 401);
        }

        $user = User::firstWhere('email', $request->email);
        $token = $user->createToken('API token for ' . $request->email)->plainTextToken;

        return $this->ok(
            'Logged in successfully',
            ['token' => $token]
        );
    }
}
