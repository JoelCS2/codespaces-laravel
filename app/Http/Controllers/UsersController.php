<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = Users::all();
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role'     => 'required|in:user,admin',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validació',
                'errors'  => $e->errors(),
            ], 422);
        }

        $validated['password'] = Hash::make($validated['password']);
        $user = Users::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        try {
            $validated = $request->validate([
                'name'     => 'sometimes|required|string|max:255',
                'email'    => 'sometimes|required|email|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:8',
                'role'     => 'sometimes|required|in:user,admin',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validació',
                'errors'  => $e->errors(),
            ], 422);
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuari eliminat correctament'], 200);
    }
}
