<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JWT;
use App\Helpers\GeneralResponse;

class UsuarioController extends Controller
{
   // Ejemplo de usuarios ficticios
    private $usuarios = [
        ['id' => 1, 'nombre' => 'Juan', 'email' => 'juan@mail.com', 'password' => '123456'],
        ['id' => 2, 'nombre' => 'María', 'email' => 'maria@mail.com', 'password' => 'abcdef'],
    ];

    /**
     * Iniciar sesión
     */
    public function iniciarSesion(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');

            if (!$email || !$password) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email y contraseña son requeridos.'
                ], 422);
            }

            // Buscar usuario
            $usuario = collect($this->usuarios)
                        ->first(fn($u) => $u['email'] === $email && $u['password'] === $password);

            if (!$usuario) {
              $response = new GeneralResponse(401, 'Credenciales incorrectas.', null);
                return response()->json($response->toArray(), 401);
            }

            // Crear JWT
            $token = JWT::create([
                'user_id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
            ]);

            $response = new GeneralResponse(200, 'login exitoso', (object) ['token' => $token]);
            return response()->json($response->toArray());

        } catch (\Throwable $e) {
          $response = new GeneralResponse(500, 'Error: ' . $e->getMessage(), null);
           return response()->json($response->toArray(), 500);
        }
    }

}
