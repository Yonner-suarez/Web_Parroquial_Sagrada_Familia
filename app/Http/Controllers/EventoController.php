<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Helpers\JWT;
use App\Helpers\NotificacionesHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class EventoController extends Controller
{
    /**
     * Obtener todos los eventos
     */
    public function index()
    {
        try {
          $eventos = Evento::orderBy('fecha_inicio', 'desc')->get()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'descripcion' => $evento->descripcion,
                'fecha_inicio' => $evento->fecha_inicio,// solo fecha
                'hora' => $evento->hora,
                'lugar' => $evento->lugar,
                'imagen' => $evento->imagen_url ? asset($evento->imagen_url) : null,
            ];
            });

            return response()->json([
                'success' => true,
                'data' => $eventos
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error al obtener eventos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los eventos.'
            ], 500);
        }
    }

    /**
     * Agregar un nuevo evento
     */
    public function agregar(Request $request)
    {
        try {

          $authHeader = $request->header('Authorization'); 
          $token = str_replace('Bearer ', '', $authHeader); 
          $payload = JWT::decode($token);
          if (!$payload || !isset($payload['user_id'])) {
              return response()->json([
                  'success' => false,
                  'message' => 'Usuario no autenticado.'
              ], 401);
          }

          $userId = $payload['user_id']; // 👈 ID del usuario autenticado
          
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
                'hora' => 'required',
                'lugar' => 'required|string|max:255',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $path = null;
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('eventos', 'public');
            }

            $evento = Evento::create([
                  'titulo' => $request->titulo,
                  'descripcion' => $request->descripcion,
                  'fecha_inicio' => $request->fecha,
                  'hora' => $request->hora,
                  'lugar' => $request->lugar,
                  'imagen_url' => $path ? "/storage/$path" : null,
                  'creado_por' => $userId,
              ]);
            NotificacionesHelper::enviarMailEvento($evento);

            return response()->json([
                'success' => true,
                'message' => 'Evento agregado correctamente.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Error al agregar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el evento.'
            ], 500);
        }
    }

    /**
     * Actualizar evento existente
     */
    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:eventos,id',
                'titulo' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'fecha' => 'sometimes|date',
                'hora' => 'sometimes',
                'lugar' => 'sometimes|string|max:255',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $evento = Evento::find($request->id);

            if (!$evento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evento no encontrado.'
                ], 404);
            }

            if ($request->hasFile('imagen')) {
                // eliminar imagen anterior si existe
                if ($evento->imagen_url && Storage::disk('public')->exists(str_replace('/storage/', '', $evento->imagen_url))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $evento->imagen_url));
                }

                $path = $request->file('imagen')->store('eventos', 'public');
                $evento->imagen_url = "/storage/$path";
            }

            $evento->update($request->only(['titulo', 'descripcion', 'fecha', 'hora', 'lugar']));
            NotificacionesHelper::enviarMailEvento($evento);

            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el evento.'
            ], 500);
        }
    }

    /**
     * Eliminar evento
     */
    public function eliminar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:eventos,id',
            ]);

            $evento = Evento::find($request->id);
            if (!$evento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evento no encontrado.'
                ], 404);
            }

            // borrar imagen del storage si existe
            if ($evento->imagen && Storage::disk('public')->exists(str_replace('/storage/', '', $evento->imagen))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $evento->imagen));
            }

            $evento->delete();

            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el evento.'
            ], 500);
        }
    }
}
