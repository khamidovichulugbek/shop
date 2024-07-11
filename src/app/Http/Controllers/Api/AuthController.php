<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $userData = Validator::make($request->all(), [
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users,email'],
                'roleId' => ['required'],
                'password' => ['required', 'confirmed']
            ]);

            if ($userData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $userData->errors()
                ], 401);
            }


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->roleId,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfuly',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $userData = Validator::make($request->all(), [
                'email' => ['required'],
                'password' => ['required']
            ]);

            if ($userData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                    'errors' => $userData->errors()
                ]);
            }

            if (!Auth::attempt($request->only([
                'email', 'password'
            ]))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password does not match',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Logged in Successfuly',
                'token' => $user->createToken('personal-token', expiresAt: now()->addDay(), abilities: ['show:user'])->plainTextToken
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function logout(Request $request){
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
        return response()->json([
            'message' => 'Successfully logged out'
            ]);
    }


}
