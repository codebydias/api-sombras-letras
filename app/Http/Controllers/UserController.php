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


        $user = User::where('email', $data['email'])->first();


        Log::info('User fetched: ', ['user' => $user]);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $validPassword = Hash::check($data['password'], $user->password);

        if (!$validPassword) {
            return response()->json(['message' => 'Senha incorreta'], 401);
        }

        $token = JWTAuth::customClaims(['id' => $user->id, 'email' => $user->email])->fromUser($user);


        return response()->json([
            'message' => 'Login efetuado com sucesso',
            'token' => $token,
            'user' => [
                'email' => $user->email,
            ],
        ]);
    }
}
