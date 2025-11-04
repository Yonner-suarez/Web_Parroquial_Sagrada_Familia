<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GaleriaImagen extends Model
{
    use Notifiable;

    protected $table = 'galeria_imagenes';

    protected $fillable = [
        'titulo',
        'descripcion',
        'url',
        'subido_por',
        'fecha_subida',
    ];
}
