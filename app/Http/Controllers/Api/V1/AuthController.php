<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\UserResource;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        return UserResource::make(User::create($request->all()));
    }

    public function login(LoginUserRequest $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['message' => '"Неверный email или пароль'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $user->tokens()->delete();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken("Token of {$user->name}")->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json([
            'user' => new UserResource(Auth::user()),
        ]);
    }
}
