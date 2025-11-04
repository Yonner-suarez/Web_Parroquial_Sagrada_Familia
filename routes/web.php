<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'components.index')->name('index');
Route::view('/noticias', 'components.noticias')->name('noticias');
Route::view('/eventos', 'components.eventos')->name('eventos');
Route::view('/horarios', 'components.horarios')->name('horarios');
Route::view('/contacto', 'components.contacto')->name('contacto');

// Routes Admin
Route::view('/administracion/Gestionar_Horarios', 'components.admin-module-gestionar-horarios')->name('administracion.gestionar-horarios');
Route::view('/administracion/Gestionar_Eventos', 'components.admin-module-gestionar-eventos')->name('administracion.gestionar-eventos');
Route::view('/administracion/Gestionar_Noticias', 'components.admin-module-gestionar-noticias')
    ->name('administracion.gestionar-noticias');
