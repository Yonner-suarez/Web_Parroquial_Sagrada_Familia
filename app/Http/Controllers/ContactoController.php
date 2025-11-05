<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JWT;
use App\Models\Contacto;
use App\Helpers\GeneralResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
  /**
     * Guardar un mensaje de contacto desde el front
     */
    public function guardar(Request $request)
    {
        try {
            // Validación de campos
            $validator = Validator::make($request->all(), [
                'nombre'  => 'required|string|max:255',
                'correo'  => 'required|email|max:255',
                'comentario' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Crear el contacto
            $contacto = Contacto::create([
                'nombre'       => $request->nombre,
                'correo'       => $request->correo,
                'asunto'       => $request->asunto ?? '',
                'mensaje'      => $request->comentario,
                'leido'        => false,
                'recibido_en'  => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado correctamente!',
                'data'    => $contacto,
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error guardando contacto: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar el mensaje.',
            ], 500);
        }
    }
}
