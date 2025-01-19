<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    // Login a user
    public function login(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user], 200);
    }

    // Logout a user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function sendResetLink(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    
        // Retrieve the user
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            // Generate a random 4-digit verification code
            $verificationCode = rand(1000, 9999);
    
            // Store the verification code in the cache (valid for 10 minutes, for example)
            Cache::put('reset_code_' . $user->email, $verificationCode, now()->addMinutes(10));
    
            // Send the verification code via email
            Mail::send('emails.reset-code', ['code' => $verificationCode], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your Password Reset Code');
            });
    
            return response()->json([
                'message' => 'A 4-digit verification code has been sent to your email.',
            ], 200);
        }
    
        return response()->json([
            'error' => 'User not found.',
        ], 404);
    }

    public function verifyCode(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric|digits:4',  // Ensure the code is a 4-digit number
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check if the code matches the one stored in the cache
        $cachedCode = Cache::get('reset_code_' . $request->email);

        // dd($cachedCode);

        if (!$cachedCode) {
            return response()->json([
                'error' => 'Verification code expired or not found.',
            ], 400);
        }

        if ($request->code != $cachedCode) {
            return response()->json([
                'error' => 'Invalid verification code.',
            ], 400);
        }

        // If the code matches, return success and email for password reset
        return response()->json([
            'message' => 'Code verified successfully. You can now reset your password.',
            'email' => $request->email, // Send the email to be used for password reset
        ], 200);
    }

    public function reset(Request $request){
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',  // Ensure password is confirmed and meets requirements
            // 'code' => 'required|numeric|digits:4',  // Ensure the code is a 4-digit number
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Verify if the code is correct
        $cachedCode = Cache::get('reset_code_' . $request->email);

        if (!$cachedCode) {
            return response()->json([
                'error' => 'Verification code expired or not found.',
            ], 400);
        }

        // if ($request->code != $cachedCode) {
        //     return response()->json([
        //         'error' => 'Invalid verification code.',
        //     ], 400);
        // }

        // Proceed to reset the password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'error' => 'User not found.',
            ], 404);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->show_password = $request->password;
        $user->save();

        // Optionally, you can clear the cache for this email now that the password is reset
        Cache::forget('reset_code_' . $request->email);

        return response()->json([
            'message' => 'Your password has been reset successfully.',
        ], 200);
    }
    
}
