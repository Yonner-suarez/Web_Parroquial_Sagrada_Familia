<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JWT;
use App\Models\Suscripcion;
use App\Helpers\GeneralResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuscripcionController extends Controller
{
  public function store(Request $request)
    {
        try {
         
            $validator = Validator::make($request->all(), [
                'correo' => 'required|email|unique:suscripciones,correo',
                'tipo' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $suscripcion = Suscripcion::create([
                'correo' => $request->correo,
                'tipo' => $request->tipo ?? 'misa'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Correo registrado correctamente',
                'data' => $suscripcion
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Error al guardar suscripción: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al registrar el correo'
            ], 500);
        }
    }
}
