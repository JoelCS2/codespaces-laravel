<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    // GET /categories
   public function index()
    {
        $categories = Categories::all();
        return response()->json(['categories' => $categories], 200); // minúscula
    }

    // GET /categories/{id}
    public function show($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria no trobada'], 404);
        }

        return response()->json($category, 200);
    }

    // POST /categories
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $category = Categories::create($request->all());
        return response()->json(['category' => $category], 201);
    }

    // PUT /categories/{id}
    public function update(Request $request, $id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria no trobada'], 404);
        }

        try {
            $validated = $request->validate([
                'name'        => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validació',
                'errors'  => $e->errors(),
            ], 422);
        }

        $category->update($validated);
        return response()->json($category, 200);
    }

    // DELETE /categories/{id}
    public function destroy($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria no trobada'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Categoria eliminada correctament'], 200);
    }
}
