<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function signup(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:100|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'email.unique' => "Este email já está em uso",
            'password.min' => 'A senha precisa ter no mínimo 6 caracteres',
        ]);


        DB::table('users')->insert([
            'id' => (string) Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Usuario criado com sucesso"
        ], 201);
    }

    public function signin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        $user = User::where('email', $data['email'])->first();


        if (!$user) {
            return response()->json(['message' => 'Email não encontrado.'], 404);
        }

        $validPassword = Hash::check($data['password'], $user->password);

        if (!$validPassword) {
            return response()->json(['message' => 'Senha incorreta'], 401);
        }

        $token = JWTAuth::customClaims(['id' => $user->id, 'email' => $user->email])->fromUser($user);

        $cookies = cookie(
            name: 'token',
            value: $token,
            minutes: 60 * 24 * 3,
            path: '/',
            domain: null,
            secure: false,
            httpOnly: true,
            raw: false,
            sameSite: 'lax'
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Login efetuado com sucesso',
            'token' => $token,
            'user' => [
                'email' => $user->email,
            ],
        ])->withCookie($cookies);
    }


    public function logout(Request $request)
    {

        $cookie = cookie('token', '', -1, '/', null, false, true, false, 'Strict');

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ])->withCookie($cookie);
    }


    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}
