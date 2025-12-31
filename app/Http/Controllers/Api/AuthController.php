<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();

        if ($errors->has('email') && str_contains($errors->first('email'), 'taken')) {
            return response()->json([
                'success' => false,
                'error_code' => 3001,
                'message' => 'Registration failed: This email is already registered.'
            ], 200); 
        }

        return response()->json([
            'success' => false,
            'error_code' => 3000,
            'message' => 'Validation error',
            'errors' => $errors
        ], 200);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'User registered successfully.',
        'user' => $user,
        'token' => $token
    ], 201);
}

    // Login user and generate token

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $tokenName = 'api_token';

        $user->tokens()->where('name', $tokenName)->delete();

        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully. .',
            'user' => $user,
            'token' => $token
        ]);
    }


    // Logout (checkin the validation)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
