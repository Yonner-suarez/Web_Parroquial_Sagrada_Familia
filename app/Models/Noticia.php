<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Noticia extends Model
{
    use Notifiable;

    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'cuerpo',
        'fecha_publicacion',
        'autor_id',
        'archivo_url',
    ];
}