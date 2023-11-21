<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input menggunakan aturan validasi
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            // Membuat pengguna baru dengan data yang divalidasi
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Menyiapkan respons JSON
            $data = [
                'message' => 'User berhasil dibuat',
                'user' => $user,
            ];

            // Mengirim respons JSON dengan kode status 201 (Created)
            return response()->json($data, 201);
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Siapkan respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Menyiapkan data input yang akan digunakan dalam percobaan login
        $credentials = $request->only('email', 'password');

        // Memeriksa apakah pengguna dapat berhasil login
        if (Auth::attempt($credentials)) {
            // Jika berhasil login, buat token untuk otentikasi
            $token = Auth::user()->createToken('auth_token');

            // Menyiapkan respons JSON dengan token
            $data = [
                'message' => 'Login berhasil',
                'token' => $token->plainTextToken,
            ];

            // Mengirim respons JSON dengan kode status 200 (OK)
            return response()->json($data, 200);
        } else {
            // Jika login gagal, menyiapkan pesan kesalahan
            $data = [
                'message' => 'Username atau Password tidak valid',
            ];

            // Mengirim respons JSON dengan kode status 401 (Unauthorized)
            return response()->json($data, 401);
        }
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Siapkan respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }
}
