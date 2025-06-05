<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\NewUserRegistered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Log::info('🟡 [NO QUEUE] Mail start at: ' . now()); // testing without queue
        $user->notify(new NewUserRegistered($user));
        //Log::info('🟡 [NO QUEUE] Mail end at: ' . now());

        $tokenResult = $user->createToken('user-token');
        $tokenPlain = $tokenResult->plainTextToken;
        $token = $tokenResult->accessToken;
        $token->expires_at = Carbon::now()->addHours(2);
        $token->save();

        return response()->json([
            'token' => $tokenPlain,
            'user'  => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $user->tokens()->where('name', 'user-token')->delete();
        $tokenResult = $user->createToken('user-token');
        $tokenPlain = $tokenResult->plainTextToken;
        $token = $tokenResult->accessToken;
        $token->expires_at = Carbon::now()->addHours(2);
        $token->save();

        return response()->json([
            'token' => $tokenPlain,
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
