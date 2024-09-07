<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function signin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('ai-assisted-web-app');
            return response()->json([
                'message' => 'login successful',
                'user' => $user,
                'token' => $token->plainTextToken,
            ]);
        }

        if(User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Password did not match'
            ], 401);
        }

        return response()->json([
            'message' => 'Email not found. Please sign up to register your account.'
        ], 401);
    }

    public function signup(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);
        $token = $user->createToken('ai-assisted-web-app')->plainTextToken;

        return response()->json([
            'message' => 'OK',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();

        $user = User::update($validated);

        return response()->json([
            'user' => $user
        ]);
    }
}
