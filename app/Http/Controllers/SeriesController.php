<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Series::all();
        return response()->json(['series' => $series], 200);
    }

    public function show($id){
        $series = Series::find($id);
        return response()->json(['series' => $series], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'title' => 'required|max:255|unique:series',
        'genre' => 'required',
        'release_year' => 'required|digits:4',
        ]);
        if ($validator->fails()) {
        return response()->json(['errors' =>

        $validator->errors()], 400);
        }
        $series = Series::create($request->all());
        return response()->json(['series' => $series], 201);
    }

    public function update(Request $request, $id)
    {
        $series = Series::find($id);
        if (!$series) {
        return response()->json(['message' => 'Serie no encontrada'], 404);
        }
        $validator = Validator::make($request->all(), [
        'title' => [

        'required',
        'max:255',
        Rule::unique('series')->ignore($series->id)

        //esto lo que hace es ignorar el id actual para que no de error de unico al actualizar el mismo registro, ya que el titulo puede ser el mismo y como hemos puesto en el store, el titulo es único, osea que solo puede haber un titulo igual en la tabla.
        ],

        'genre' => 'required',
        'release_year' => 'required|digits:4',

        ]);

        if ($validator->fails()) {
        return response()->json(['errors' =>

        $validator->errors()], 400);
        }
        $series->update($request->all());
        return response()->json(['series' => $series], 200);
    }

    public function destroy($id){
        $series = Series::find($id);
        if (!$series) {
            return response()->json(['message' => 'Serie no encontrada'], 404);
        }
        $series->delete();
        return response()->json(['message' => 'Serie eliminada correctamente'], 200);
    }
}
