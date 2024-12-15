<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Constructor: Aplica middleware para JWT
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'cedula' => 'nullable|string',
            'implementos' => 'nullable|string',
            'rol' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cedula' => $request->cedula,
            'implementos' => $request->implementos,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Mostrar un usuario específico.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Actualizar un usuario existente.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'string|min:6',
            'cedula' => 'nullable|string',
            'implementos' => 'nullable|string',
            'rol' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'cedula' => $request->cedula ?? $user->cedula,
            'implementos' => $request->implementos ?? $user->implementos,
            'rol' => $request->rol ?? $user->rol,
            'estado' => $request->estado ?? $user->estado,
        ]);

        return response()->json($user, 200);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }



    public function uploadImages(Request $request, $id)
    {
        // Buscar el usuario por ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Validar las imágenes
        $request->validate([
            'perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'implemento1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'implemento2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'implemento3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Subir la imagen de perfil
        if ($request->hasFile('perfil')) {
            $user->perfil = $request->file('perfil')->store('uploads/perfil', 'public');
        }

        // Subir implementos individuales
        if ($request->hasFile('implemento1')) {
            $user->implemento1 = $request->file('implemento1')->store('uploads/implementos', 'public');
        }

        if ($request->hasFile('implemento2')) {
            $user->implemento2 = $request->file('implemento2')->store('uploads/implementos', 'public');
        }

        if ($request->hasFile('implemento3')) {
            $user->implemento3 = $request->file('implemento3')->store('uploads/implementos', 'public');
        }

        // Guardar los cambios en el usuario
        $user->save();

        return response()->json([
            'message' => 'Imágenes subidas con éxito',
            'user' => $user,
        ], 200);
    }


}
