<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    /**
     * Login
     *
     * Authenticates the user and returns your generated API token.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
    "message": "Authenticated",
    "data": {
        "token": "15|XpIxvsWpD5oVfVZRWN05Tz0wOg4pL4cNuCstfM5h1157c728"
    },
    "status ": 200
     * }
     */
    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('Invalid credentials', 401);
        }

        /** @var User */
        $user = User::query()->where('email', $request->email)->firstOrFail();

        return $this->ok('Authenticated', [
            'token' => $user->createToken(
                'API token for ' . $user->email,
                Abilities::forUser($user),
            )->plainTextToken,
        ]);
    }

    /**
     * Logout
     *
     * Signs out the authenticated user and destroys the API token.
     *
     * @group Authentication
     */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok();
    }

    public function register()
    {
        return $this->ok('Register');
    }
}
