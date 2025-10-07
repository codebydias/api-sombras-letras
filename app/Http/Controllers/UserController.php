<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function signup(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $emailExists = DB::table('users')->where('email', $data['email'])->exists();

        if ($emailExists) {
            return response()->json([
                'message' => "O email {$data['email']} já está em uso"
            ], 409);
        }


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
        $user = DB::table('users')->where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $validPassword = Hash::check($data['password'], $user->password);

        if (!$validPassword) {
            return response()->json(['message' => 'Senha incorreta'], 401);
        }
    }
}
