<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contacto extends Model
{
    use Notifiable;

    protected $table = 'contactos';

    protected $fillable = [
        'nombre',
        'correo',
        'asunto',
        'mensaje',
        'leido',
        'recibido_en',
    ];

    protected $attributes = [
        'leido' => false,
    ];
}