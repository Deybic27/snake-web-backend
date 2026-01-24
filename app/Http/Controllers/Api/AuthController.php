<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'username' => 'required|string|min:1|max:30|unique:users,username|regex:/^[a-z0-9._]+$/',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:100|confirmed',
        ]);

        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        // Return response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string|max:255|exists:users,username',
            'password' => 'required|string|min:8|max:100'  
            ],
            [
                'username.regex' => 'The username may only contain letters, numbers, dots and underscores.',
                'username.exists' => 'User not found.',
            ]);

        $user = User::where('username', $credentials['username'])->first();
        if(!$user || !Hash::check($credentials['password'], $user->password))
        {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]
        ], 200);
    }

    // User logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}
