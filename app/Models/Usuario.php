<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Usuario extends Model
{
    use Notifiable;
    public $timestamps = false;

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
