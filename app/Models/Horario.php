<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Horario extends Model
{
    use Notifiable;
    public $timestamps = false;

    protected $table = 'horarios';

    protected $fillable = [
        'dia',
        'hora',
        'capilla',
        'descripcion',
        'creado_en',
    ];
}
