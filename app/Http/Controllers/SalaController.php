<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    /**
     * Listar todas las salas.
     */
    public function index()
    {
        $salas = Sala::with('eventos')->get(); // Incluye eventos relacionados
        return response()->json($salas, 200);
    }

    /**
     * Crear una nueva sala.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $sala = Sala::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($sala, 201);
    }

    /**
     * Mostrar una sala específica.
     */
    public function show($id)
    {
        $sala = Sala::with('eventos')->find($id);

        if (!$sala) {
            return response()->json(['error' => 'Sala no encontrada'], 404);
        }

        return response()->json($sala, 200);
    }

    /**
     * Actualizar una sala.
     */
    public function update(Request $request, $id)
    {
        $sala = Sala::find($id);

        if (!$sala) {
            return response()->json(['error' => 'Sala no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'string|max:255',
        ]);

        $sala->update($request->all());

        return response()->json($sala, 200);
    }

    /**
     * Eliminar una sala.
     */
    public function destroy($id)
    {
        $sala = Sala::find($id);

        if (!$sala) {
            return response()->json(['error' => 'Sala no encontrada'], 404);
        }

        $sala->delete();

        return response()->json(['message' => 'Sala eliminada con éxito'], 200);
    }
}
