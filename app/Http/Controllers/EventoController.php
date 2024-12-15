<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Sala;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Listar todos los eventos.
     */
    public function index()
    {
        $eventos = Evento::with('sala')->get(); // Incluye la información de la sala relacionada
        return response()->json($eventos, 200);
    }

    /**
     * Crear un nuevo evento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'sala_id' => 'required|exists:salas,id', // Verifica que la sala exista
        ]);

        $evento = Evento::create($request->all());

        return response()->json($evento, 201);
    }

    /**
     * Mostrar un evento específico.
     */
    public function show($id)
    {
        $evento = Evento::with('sala')->find($id);

        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        return response()->json($evento, 200);
    }

    /**
     * Actualizar un evento.
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'string|max:255',
            'fecha' => 'date',
            'sala_id' => 'exists:salas,id', // Verifica que la sala exista si se actualiza
        ]);

        $evento->update($request->all());

        return response()->json($evento, 200);
    }

    /**
     * Eliminar un evento.
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        $evento->delete();

        return response()->json(['message' => 'Evento eliminado con éxito'], 200);
    }
}
