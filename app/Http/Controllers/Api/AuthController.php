<?php

namespace App\Http\Controllers\Api;

use App\Actions\Cart\MergeGuestCartAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // DocBlock

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $user->assignRole('customer');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'user registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(LoginRequest $request, MergeGuestCartAction $mergeAction)
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        $guestToken = $request->header('X-Cart-Token');
        if($guestToken) {
            $mergeAction->execute($guestToken, $user);
        }

        return response()->json([
            'status' => true,
            'message' => 'logged in successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'logged out successfully'
        ]);
    }
}
