<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;
use Illuminate\Support\Facades\Log;

class NoticiaController extends Controller
{
    /**
     * Obtener todas las noticias
     */
    public function index()
{
    try {
        $noticias = Noticia::orderBy('fecha_publicacion', 'desc')->get()->map(function ($noticia) {
        return [
            'id' => $noticia->id,
            'titulo' => $noticia->titulo,
            'cuerpo' => $noticia->cuerpo,
            'fecha_publicacion' => $noticia->fecha_publicacion,
            'imagen' => $noticia->archivo_url ? asset($noticia->archivo_url) : null,
              ];
          });
          return response()->json(['success' => true, 'data' => $noticias], 200);

    } catch (\Throwable $e) {
        Log::error('Error al obtener noticias: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener las noticias.'
        ], 500);
    }
}

    /**
     * Agregar una nueva noticia
     */
     public function agregar(Request $request)
    {
                try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $path = null;
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('noticias', 'public');
            }

            Noticia::create([
                'titulo' => $validated['titulo'],
                'cuerpo' => $validated['cuerpo'] ?? '',
                'fecha_publicacion' => now(),
                'autor_id' => auth()->id() ?? 1, // o algún valor por defecto
                'archivo_url' => $path ? "/storage/$path" : null,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error al agregar noticia: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno al guardar la noticia'], 500);
        }
    }

    

    /**
     * Actualizar noticia existente
     */
    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:noticias,id',
                'titulo' => 'sometimes|string|max:255',
                'descripcion' => 'sometimes|string',
                'imagen' => 'nullable|image|max:2048',
            ]);

            $noticia = Noticia::find($request->id);

            if (!$noticia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Noticia no encontrada.'
                ], 404);
            }

            
            if ($request->hasFile('imagen')) {
              $path = $request->file('imagen')->store('noticias', 'public');
                            $noticia->archivo_url = "/storage/$path";
            } 

            $noticia->update($request->only(['titulo', 'descripcion']));

            return response()->json([
                'success' => true,
                'message' => 'Noticia actualizada correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al actualizar noticia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la noticia.'
            ], 500);
        }
    }

    /**
     * Eliminar noticia
     */
    public function eliminar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:noticias,id'
            ]);

            $noticia = Noticia::find($request->id);

            if (!$noticia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Noticia no encontrada.'
                ], 404);
            }

            $noticia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Noticia eliminada correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            Log::error('Error al eliminar noticia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la noticia.'
            ], 500);
        }
    }
}
