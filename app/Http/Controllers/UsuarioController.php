<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JWT;
use App\Models\Usuario;
use App\Helpers\GeneralResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Iniciar sesión (ejemplo con JWT)
     */
  public function iniciarSesion(Request $request)
{
    try {
        // Validación básica
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->email)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Desencriptar la contraseña usando AES en MySQL
        $secretKey = env('SECRET_KEY_HASH', 'pruebaSecreta');

        $passDec = \DB::selectOne(
            "SELECT CONVERT(AES_DECRYPT(UNHEX(?), ?) USING UTF8) AS pass",
            [$usuario->password_hash, $secretKey]
        );


        // Validar contraseña
        if ($passDec->pass !== $request->password) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Crear JWT
        $token = JWT::create([
            'user_id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'email' => $usuario->correo,
            'rol' => $usuario->rol,
        ]);

        return response()->json([
            'success' => true,
            'token' => $token
        ], 200);

    } catch (\Throwable $e) {
        Log::error('Error iniciar sesión: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error al iniciar sesión.'], 500);
    }
}


    /**
     * Listar todos los usuarios
     */
    public function index(Request $request)
    {
        try {
            $payload = $this->getUserPayload($request);
            if (!$payload) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            $usuarios = Usuario::orderBy('nombre', 'asc')->get();
            return response()->json(['success' => true, 'data' => $usuarios], 200);

        } catch (\Throwable $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener usuarios.'], 500);
        }
    }

    /**
     * Agregar nuevo usuario
     */
    public function agregar(Request $request)
    {
        try {
            $payload = $this->getUserPayload($request);
            if (!$payload) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:usuarios,correo',
                'rol' => 'required|string',
                'password' => 'required|string',
            ]);

            // Encriptar la contraseña usando AES en MySQL
            $secretKey = env('SECRET_KEY_HASH', 'pruebaSecreta');

            $passDec = \DB::selectOne(
                "SELECT HEX( AES_ENCRYPT ( CONVERT (? USING UTF8), ?)) AS pass",
                [$request->password, $secretKey]
            );

            Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->email,
                'rol' => $request->rol,
                'password_hash' => $passDec->pass,
            ]);

            return response()->json(['success' => true, 'message' => 'Usuario agregado correctamente.'], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);

        } catch (\Throwable $e) {
            Log::error('Error al agregar usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al agregar usuario.'], 500);
        }
    }

    /**
     * Eliminar usuario
     */
    public function eliminar(Request $request)
    {
        try {
            $payload = $this->getUserPayload($request);
            if (!$payload) {
                return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
            }

            $request->validate(['id' => 'required|exists:usuarios,id']);

            $usuario = Usuario::find($request->id);
            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);

        } catch (\Throwable $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar usuario.'], 500);
        }
    }

    /**
     * Decodificar JWT enviado en headers
     */
    private function getUserPayload(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader) return null;

        $token = str_replace('Bearer ', '', $authHeader);
        return JWT::decode($token);
    }
}
