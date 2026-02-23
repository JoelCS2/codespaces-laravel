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

    //Parte A: Validacion de datos y creación de usuario, una vez obetnido la información del request, se valida con el validador de Laravel

    public function register (Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,user'
        ]);

        //Parte B: Si la validación falla, se devuelve un error con los mensajes de validación.

        if ($validator -> fails()){
            return response()->json($validator->errors(), 422);
        }

        //Como ya tenemos la validación, podemos crear el usuario con lo que tenga request que es lo que el cliente envió y que ya hemos validado, pero como la contraseña no se puede guardar en texto plano, se encripta con bcrypt antes de guardarla.

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role')
        ]);

        //Parte C: Finalmente, se devuelve una respuesta JSON con un mensaje de éxito y los datos del usuario creado.

        return response()->json([
            'user' => $user,
            'message' => 'Usuario registrado correctamente'
        ], 201);

    }

    //funcion para logearnos

    public function login (Request $request){

        $validator = Validator:: make ($request -> all(),[
            'email' =>'required|string|email|max:100',
            'password' => 'required|string|min:8'
        ]);

        if ($validator -> fails()){
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request-> only([
            'email',
            'password'
        ]);

        //validar las credenciales de usuario y contraseña
        try{

            if (!$token = JWTAuth ::attempt($credentials)){
                return response()-> json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
            return response() ->json([
                'message' => 'User logged in successfully',
                'token' => $token,
            ], 200);

        }catch (JWTExcepetion $e){

            return response ()-> json ([
                'error' => 'Could not create token',
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    //funcion para sacar mis datos del propio usuario /me ---> getUser()

    public function getUser(){
        $user = Auth::user();
        return response()->json([
            'MI PERFIL' => $user,
        ],200);

    }


    //funcion para cerrar sesion, logout destruye el token creado anteriormente
    public function logout(){
        try{

            JWTAuth::invalidate(JWTAuth::getToken());
            return response ()-> json([
                'message' => 'Token invalidado correctamente'
            ], 200);
        }catch(JWTException $e){
            return response ()->json([
                'message' => 'Error al intentar invalidar el token'
            ], 500);
        }
    }

}
