<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    /**
     * Register user baru
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            Log::info('Register gagal', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => 'Penumpang'
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        Log::info('User berhasil register', ['id' => $user->id, 'username' => $user->username]);

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            Log::warning('Login gagal', ['username' => $request->username]);
            return response()->json([
                'message' => 'unauthorized'
            ], 401);
        }

        $user = User::where('username', $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User login berhasil', ['id' => $user->id, 'username' => $user->username]);

        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        Log::info('User logout', ['id' => $user->id, 'username' => $user->username]);

        return response()->json(['message' => 'Logged out'], 200);
    }

    /**
     * Get user data (protected route)
     */
    public function userProfile(Request $request)
    {
        Log::info('Akses userProfile', ['id' => $request->user()->id]);
        return response()->json($request->user());
    }
}
