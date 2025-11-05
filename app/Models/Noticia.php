<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Noticia extends Model
{
    use Notifiable;
    public $timestamps = false;

    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'cuerpo',
        'fecha_publicacion',
        'autor_id',
        'archivo_url',
    ];
}