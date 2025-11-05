<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HorarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UsuarioController::class, 'iniciarSesion']);
Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios/agregar', [UsuarioController::class, 'agregar']);
Route::delete('/usuarios/eliminar', [UsuarioController::class, 'eliminar']);

Route::get('/noticias', [NoticiaController::class, 'index']);
Route::post('/noticias/agregar', [NoticiaController::class, 'agregar']);
Route::put('/noticias/actualizar', [NoticiaController::class, 'actualizar']);
Route::delete('/noticias/eliminar', [NoticiaController::class, 'eliminar']);

Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos/agregar', [EventoController::class, 'agregar']);
Route::put('/eventos/actualizar', [EventoController::class, 'actualizar']);
Route::delete('/eventos/eliminar', [EventoController::class, 'eliminar']);

Route::get('/horarios', [HorarioController::class, 'index']);
Route::get('/horarios/sectores', [HorarioController::class, 'sectores']);
Route::post('/horarios/agregar', [HorarioController::class, 'agregar']);
Route::put('/horarios/actualizar', [HorarioController::class, 'actualizar']);
Route::delete('/horarios/eliminar', [HorarioController::class, 'eliminar']);
Route::get('/horarios/filtrar', [HorarioController::class, 'filtrarHorarios']);