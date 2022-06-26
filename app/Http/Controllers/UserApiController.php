<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function showUser($id = null)
    {

        if ($id == '') {
            $user = User::all();
            return response()->json([
                'users'  => $user,
                'status' => true,
            ], 200);
        } else {
            $user = User::find($id);
            return response()->json([
                'user'   => $user,
                'status' => true,
            ], 200);
        }
    }

    public function addUser(Request $request)
    {
        // return $request->all();
        $validation = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|min:6|max:25',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors(),
                'status' => false,
            ], 400);
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
                'data' => $th->getMessage(),
                'status' => false,
            ], 202);
        }
    }

    public function addUserMultiple(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $validation = Validator::make($value, [
                'name'     => 'required|string',
                'email'    => 'required|email:rfc,dns',
                'password' => 'required|min:6|max:25',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message' => $validation->errors(),
                    'status' => false,
                ], 400);
            }
        }


        try {
            foreach ($request->all() as $key => $data) {
                $user[$key] = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
            }


            return response()->json([
                'message' => 'User create successfully',
                'data' => $user,
                'status' => true,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User create failed',
                'data' => $th->getMessage(),
                'status' => false,
            ], 202);
        }
    }
}