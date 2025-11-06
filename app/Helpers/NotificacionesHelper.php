<?php

namespace App\Helpers;

use App\Models\Suscripcion;
use App\Models\Usuario;
use App\Mail\EventoNotificacionMail;
use Illuminate\Support\Facades\Mail;

class NotificacionesHelper
{
  public static function enviarMailEvento($evento)
{
    // Obtener todos los correos de suscriptores
    $correos = Suscripcion::pluck('correo');

    // Ruta del template HTML
    $templatePath = app_path('templates/evento.html');

    if (!file_exists($templatePath)) {
        \Log::error("Template de evento no encontrado en: $templatePath");
        return;
    }

    // Leer el contenido del HTML
    $template = file_get_contents($templatePath);

    // Reemplazar variables en el template
    $template = str_replace('{{titulo}}', $evento->titulo ?? '', $template);
    $template = str_replace('{{descripcion}}', $evento->descripcion ?? '', $template);
    $template = str_replace('{{fecha}}', $evento->fecha ?? '', $template);
    $template = str_replace('{{hora}}', $evento->hora ?? '', $template);
    $template = str_replace('{{lugar}}', $evento->lugar ?? '', $template);
    $template = str_replace('{{imagen_url}}', $evento->imagen_url ?? '', $template);

    foreach ($correos as $correo) {
    try {
        Mail::send([], [], function ($message) use ($correo, $evento, $template) {
            $message->to($correo)
                    ->subject('Nuevo Evento: ' . ($evento->titulo ?? ''))
                    ->html($template);
        });

        usleep(500000); // 0.5 segundos de espera entre correos
    } catch (\Throwable $e) {
        \Log::error("Error enviando mail a $correo: " . $e->getMessage());
    }
}
}

     /**
     * Envía correo a todos los usuarios con la información del contacto recibido
     */
  public static function enviarMailContacto($contacto)
{
    // Obtener todos los correos de usuarios
    $correos = Usuario::pluck('correo');

    // Ruta del template HTML
    $templatePath = app_path('templates/contacto.html');

    if (!file_exists($templatePath)) {
        \Log::error("Template de contacto no encontrado en: $templatePath");
        return;
    }

    // Leer el contenido del HTML
    $template = file_get_contents($templatePath);

    // Reemplazar variables en el template
    $template = str_replace('{{nombre}}', $contacto->nombre ?? '', $template);
    $template = str_replace('{{correo}}', $contacto->correo ?? '', $template);
    $template = str_replace('{{asunto}}', $contacto->asunto ?? '', $template);
    $template = str_replace('{{mensaje}}', $contacto->mensaje ?? '', $template);
    $template = str_replace('{{recibido_en}}', $contacto->recibido_en ?? '', $template);

    // Enviar correo a cada usuario
    foreach ($correos as $correo) {
        try {
            Mail::send([], [], function ($message) use ($correo, $template) {
                $message->to($correo)
                        ->subject('Nuevo mensaje de contacto')
                        ->html($template); // <-- usar html() en lugar de setBody()
            });
        } catch (\Throwable $e) {
            \Log::error("Error enviando mail a $correo: " . $e->getMessage());
        }
    }
}

}
