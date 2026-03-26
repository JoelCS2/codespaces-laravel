<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get(), 200);
    }

    public function show($id)
    {
        $product = Products::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Producte no trobat'], 404);
        }

        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'size'        => 'nullable|string|max:10',
            'color'       => 'nullable|string|max:50',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product = Products::create($request->all());
        return response()->json(['product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Producte no trobat'], 404);
        }

        try {
            $validated = $request->validate([
                'name'        => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'sometimes|required|numeric|min:0',
                'stock'       => 'sometimes|required|integer|min:0',
                'size'        => 'nullable|string|max:10',
                'color'       => 'nullable|string|max:50',
                'category_id' => 'sometimes|required|integer|exists:categories,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validació',
                'errors'  => $e->errors(),
            ], 422);
        }

        $product->update($validated);
        return response()->json($product->load('categories'), 200);
    }

    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Producte no trobat'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Producte eliminat correctament'], 200);
    }
}
