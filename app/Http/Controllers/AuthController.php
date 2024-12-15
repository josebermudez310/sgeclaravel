<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }






















    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600,
            'user' => [
                'id' => auth()->user()->id,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'cedula' => 'string|min:6',
            'implementos' => 'string|min:6',
            'rol' => 'string|min:6',
            'estado' => 'string',


        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }





    public function updatePasswordById(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Buscar el usuario por ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Actualizar la contraseña del usuario
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada con éxito para el usuario con ID: ' . $id], 200);
    }













    public function uploadFile(Request $request)
{
    // Validar los datos
    $request->validate([
        'file' => 'required|file|mimes:jpeg,png,pdf|max:2048', // Mimes pueden ser ajustados según el tipo de archivo permitido
    ]);

    // Obtener el usuario autenticado
    $user = auth()->user();

    // Manejar la subida del archivo
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->store('uploads', 'public'); // Guardar el archivo en la carpeta 'uploads'

        // Actualizar el modelo con la ruta del archivo
        $user->file_path = $path;
        $user->save();

        return response()->json(['message' => 'Archivo subido exitosamente', 'file_path' => $path], 200);
    }

    return response()->json(['error' => 'Error al subir el archivo'], 500);
}

}
