<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Suscripcion extends Model
{
    use Notifiable;

    protected $table = 'suscripciones';

    protected $fillable = [
        'correo',
        'tipo',
        'usuario_id',
        'creado_en',
    ];
}