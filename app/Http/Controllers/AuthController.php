<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request) : JsonResponse
    {
        //authenticate user by email and password
        $token = auth('api')->attempt($request->only('email', 'password'));
        if (!$token) {
            return response()->json([
                'error' => 'Invalid email or password'
            ], 403);
        } else {
            return response()->json([
                'token' => $token,
            ]);
        }

    }

    public function logout(){
        auth('api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function refresh(){
        /** @var \Tymon\JWTAuth\JWTGuard $authGuard */ //php intelephense helper for $authGuard to ignore this variable
        $authGuard = auth('api');
        $token = $authGuard->refresh();
        return response()->json([
            'token' => $token,
        ]);
    }

    public function me(){
        $user = auth('api')->user();
        return response()->json([
            'user' => $user
        ]);
    }
}
