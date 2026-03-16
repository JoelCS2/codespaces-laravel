<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register (Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
        ]);


        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'Usuario registrado correctamente'
        ], 201);
    }

    public function login (Request $request){
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
            return response()->json([
                'token' => $token,
                'message' => 'Inicio de sesión exitoso'
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

    }

    public function getUser(){
        $user = Auth::user();
        return response()->json(['message' => 'Usuario autenticado',
        'data' => $user], 200);
    }

    public function logout(Request $request) {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Usuario desconectado correctamente'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo invalidar el token'], 500);
        }
    }
}
