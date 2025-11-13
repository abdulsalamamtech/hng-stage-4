<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Login user and get token.
     * @param Request $request
     */
    public function login(Request $request)
    {
        // login logic here email and password
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            Log::warning("Login attempt failed for email: " . $request->email);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('MyAppToken')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => $user,
            'token' => $token
        ], 200);
    }


    /**
     * Register new user and get a token.
     * @param Request $request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::error("Register validation failed");
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => $validator->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('MyAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => $user,
            'token' => $token
        ], 201);
    }


    /**
     * Logout the user's and destroy token.
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
            'data' => null
        ]);
    }


    /**
     * Logout the user's and destroy token from all devices.
     * @param Request $request
     */
    public function logoutDevices(Request $request)
    {
        // $user->tokens()->delete();
        $request->user()->tokens()->delete();
        $message = 'You have successfully logged out from all devices.';
        Log::info("User logged out from all devices", ['user_id' => $request->user()->id]);
        // Return success response
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => null
        ]);
    }
}
