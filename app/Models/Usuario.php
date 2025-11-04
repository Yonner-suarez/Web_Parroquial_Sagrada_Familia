<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Usuario extends Model
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password_hash',
        'rol',
        'creado_en',
    ];

    protected $attributes = [
        'rol' => 'visitante',
    ];
}
