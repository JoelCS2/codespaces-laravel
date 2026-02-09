<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return response()->json(['movies' => $movies], 200);
    }

    public function show($id){

        $movie = Movie::find($id);
        if (!$movie){

            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        return response()->json(['movie' => $movie], 200);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'title' => 'required|max:255|unique:movies',
        'duration' => 'nullable|integer',
        'release_year' => 'nullable|digits:4',
        ]);
        if ($validator->fails()) {
        return response()->json(['errors' =>

        $validator->errors()], 400);
        }
        $movie = Movie::create($request->all());
        return response()->json(['movie' => $movie], 201);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
        return response()->json(['message' => 'Película no encontrada'], 404);
        }
        $validator = Validator::make($request->all(), [
        'title' => [

        'required',
        'max:255',
        Rule::unique('movies')->ignore($movie->id)

        //esto lo que hace es ignorar el id actual para que no de error de unico al actualizar el mismo registro, ya que el titulo puede ser el mismo y como hemos puesto en el store, el titulo es único, osea que solo puede haber un titulo igual en la tabla.
        ],

        'duration' => 'required',
        'release_year' => 'required|digits:4',

        ]);

        if ($validator->fails()) {
        return response()->json(['errors' =>

        $validator->errors()], 400);
        }
        $movie->update($request->all());
        return response()->json(['movie' => $movie], 200);
    }

    public function destroy($id){
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }
        $movie->delete();
        return response()->json(['message' => 'Película eliminada correctamente'], 200);
    }

}
