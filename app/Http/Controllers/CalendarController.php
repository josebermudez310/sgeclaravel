<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Importar la clase Log
use Illuminate\Support\Facades\Validator;
use App\Models\Calendar; 

class CalendarController extends Controller
{
   
    public function store(Request $request)
    {
        \Log::info('Datos recibidos:', $request->all());
    
        // Separar la fecha y hora del campo 'date'
        $dateTime = $request->input('date'); // Ejemplo: 2024-12-06T02:45
        $date = date('Y-m-d', strtotime($dateTime)); // Extrae la fecha
        $time = date('H:i:s', strtotime($dateTime)); // Extrae la hora
    
        // Añadir fecha y hora al request antes de validarlo
        $request->merge([
            'date' => $date,
            'time' => $time
        ]);
    
        // Validar los datos del request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s', // Validar la hora
            'fullName' => 'nullable|string|max:255',
            'idNumber' => 'nullable|string|max:50',
        ]);
    
        // Si la validación falla, devuelve errores
        if ($validator->fails()) {
            Log::error('Errores de validación:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Crear un nuevo evento con fecha y hora separadas
        $event = Calendar::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'fullName' => $request->fullName,
            'idNumber' => $request->idNumber,
        ]);
    
    
        Log::info('Evento creado exitosamente:', ['event' => $event]);
    
        return response()->json(['message' => 'Evento creado exitosamente', 'event' => $event], 201);
    }
    
    public function index()
    {
        $events = Calendar::all()->map(function ($event) {
            return [
                'title' => $event->title,
                'date' => $event->date,    
                'time' => $event->time,  
                'description' => $event->description,
                'fullName' => $event->fullName,
                'idNumber' => $event->idNumber,
            ];
        });
    
        return response()->json(['events' => $events], 200);
    }
    
}
