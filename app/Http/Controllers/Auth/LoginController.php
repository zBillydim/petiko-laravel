<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        $token = $user->createToken('api_token')->plainTextToken;
        if ($user && Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Login successful', 'user' => $user ,'token' => $token], 200);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
