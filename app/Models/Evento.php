<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Evento extends Model
{
    use Notifiable;
    public $timestamps = false;

    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'lugar',
        'imagen_url',
        'creado_por',
        'creado_en',
        'hora'
    ];
    // --- Esto asegura que Laravel transforme a Carbon ---
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'creado_en' => 'datetime',
    ];
}