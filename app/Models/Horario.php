<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Horario extends Model
{
    use Notifiable;

    protected $table = 'horarios';

    protected $fillable = [
        'dia',
        'hora',
        'capilla',
        'descripcion',
        'creado_en',
    ];
}
