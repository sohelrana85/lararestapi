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
        // return $request->all();
        $validation = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:6|max:25',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
                'status' => false,
            ], 422);
        }

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User create successfully',
                'data' => $user,
                'status' => true,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User create failed',
                'errors' => $th->getMessage(),
                'status' => false,
            ], $th->getCode());
        }
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|min:6|max:25',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
                'status' => false,
            ], 422);
        }

        try {
            $data = [
                'email'  => $request->email,
                'password' => $request->password,
            ];

            if (Auth::attempt($data)) {
                $user = Auth::user();
                $access_token = $user->createToken('accessToken')->accessToken;

                return response()->json([
                    'status' => true,
                    'message' => 'User Login success',
                    'token' => $access_token,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are unauthorize',
                    'status' => false,
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User create failed',
                'errors' => $th->getMessage(),
                'status' => false,
            ], $th->getCode());
        }
    }

    public function logout()
    {
        return auth()->user()->token()->revoke();
    }
    public function userShow($id)
    {
        return User::find($id);
    }
}