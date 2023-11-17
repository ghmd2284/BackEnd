<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password

        ];
        $user = User::create($input);

        $data = [
            'mesaage' => 'User is Created Successfully',
            'data' => $user
            
        ];

        return response()->json($data,200);
    }

    public function login(Request $request) {
        $input = [
            'name' => $request->name,
            'password' => $request->password
        ];

        if (Auth::attempt($input)) {
            $token = Auth::user()->createToken('auth_token');
            $data = [
                'mesaage' => 'Login Successfully',
                'data' => $token->plainTextToken
            ];
            return response()->json($data,200);
        } else {
            $data = [
                'mesaage' => 'Username Or Password Invalid',
            ];
            return response()->json($data,200);
        }
        
    }
}
