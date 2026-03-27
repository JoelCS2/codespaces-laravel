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
    public function login(Request $request){

        $validator = Validator::make($request->all(), [

            'email'=>'required|string|email|max:100',
            'password'=>'required|string|min:5',

        ]);

        if ($validator->fails()){

            return response()->json($validator->errors(), 422);

        }

        $credentials = $request->only(['email', 'password']);

        try{

        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json([
                'message'=> 'Credenciales inválidas'
            ], 401);
        }

        return response()->json([
            'message'=> 'Has iniciado sesión de manera exitosa',
            'token'=>$token
        ], 200);

        }catch (JWTException $e){
            return response()->json([
                'error'=>'No se ha podido crear el token correctamente',
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [

            'name'=>'required|string|max:100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:5|confirmed',
            'role'=>'required|string|max:20|in:admin,user'

        ]);

        if($validator->fails()){

            return response()->json($validator->errors(), 422);

        }

        $user = User::create([

            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'role'=>$request->get('role'),
            'password'=> bcrypt($request->get('password'))

        ]);

        return response()->json([
            'message'=>'Usuario creado con éxito.',
            'data'=>$user
        ], 201);

    }

    public function logout(){

        try{

            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'message'=> 'Has cerrado sesión con éxito.'
            ], 200);

        } catch (JWTException $e){

            return response()->json(['message'=> 'No has podido cerrar sesión.'], 500);

        }
    }


    public function getUser(){

        $user = Auth::user();

        return response()->json(['message'=>'Usuario recibio correctamente','data'=>$user], 200);
    }

}
