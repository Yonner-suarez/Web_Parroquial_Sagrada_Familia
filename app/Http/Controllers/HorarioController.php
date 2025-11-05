<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use Illuminate\Support\Facades\Log;

class HorarioController extends Controller
{
    /**
     * Obtener todos los horarios
     */
    public function index()
    {
        try {
            $horarios = Horario::orderBy('dia', 'asc')
                ->orderBy('hora', 'asc')
                ->get();

            // Mapeo para frontend
            $horarios = $horarios->map(function ($horario) {
                return [
                    'id' => $horario->id,
                    'dia' => $horario->dia,
                    'hora' => $horario->hora,
                    'capilla' => $horario->capilla,
                    'descripcion' => $horario->descripcion,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $horarios
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Error al obtener horarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los horarios.'
            ], 500);
        }
    }

    /**
     * Agregar nuevo horario
     */
    public function agregar(Request $request)
    {
        try {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'sector' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
            ]);

            Horario::create([
                'dia' => $request->fecha,
                'hora' => $request->hora,
                'capilla' => $request->sector,
                'descripcion' => $request->descripcion ?? null,
                'creado_en' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horario agregado correctamente.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al agregar horario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el horario.'
            ], 500);
        }
    }

    /**
     * Actualizar horario existente
     */
    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:horarios,id',
                'fecha' => 'sometimes|date',
                'hora' => 'sometimes',
                'sector' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
            ]);

            $horario = Horario::find($request->id);

            if (!$horario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Horario no encontrado.'
                ], 404);
            }

            $horario->update([
                'dia' => $request->input('fecha', $horario->dia),
                'hora' => $request->input('hora', $horario->hora),
                'capilla' => $request->input('sector', $horario->capilla),
                'descripcion' => $request->input('descripcion', $horario->descripcion),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horario actualizado correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al actualizar horario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el horario.'
            ], 500);
        }
    }

    /**
     * Eliminar horario
     */
    public function eliminar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:horarios,id',
            ]);

            $horario = Horario::find($request->id);

            if (!$horario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Horario no encontrado.'
                ], 404);
            }

            $horario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Horario eliminado correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al eliminar horario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el horario.'
            ], 500);
        }
    }
     public function sectores()
    {
        try {
            $sectores = Horario::select('capilla')->distinct()->pluck('capilla');
            return response()->json([
                'success' => true,
                'data' => $sectores
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error obteniendo sectores: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo sectores.'
            ], 500);
        }
    }
    // En HorarioController.php
public function filtrarHorarios(Request $request)
{
    try {
        $fecha = $request->query('fecha');
        $lugar = $request->query('lugar');

        $query = Horario::query();

        if ($fecha) {
            $query->whereDate('dia', $fecha);
        }

        if ($lugar) {
            $query->where('capilla', $lugar);
        }

        $horarios = $query->orderBy('dia', 'asc')
                          ->orderBy('hora', 'asc')
                          ->get();

        $horarios = $horarios->map(function ($h) {
            return [
                'id' => $h->id,
                'dia' => $h->dia,
                'hora' => $h->hora,
                'capilla' => $h->capilla,
                'descripcion' => $h->descripcion,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $horarios
        ], 200);

    } catch (\Throwable $e) {
        Log::error('Error filtrando horarios: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error filtrando horarios.'
        ], 500);
    }
}

}
