<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Usuario;
use App\Models\Horario;
use App\Models\Evento;
use App\Models\Noticia;
use App\Models\Contacto;
use App\Models\GaleriaImagenes;
use App\Models\Suscripcion;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Usuario::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'correo' => $faker->unique()->safeEmail,
        'password_hash' => bcrypt('password'),
        'rol' => $faker->randomElement(['administrador', 'visitante']),
        'creado_en' => now(),
    ];
});

$factory->define(Horario::class, function (Faker $faker) {
    return [
        'dia' => $faker->dayOfWeek,
        'hora' => $faker->time('H:i'),
        'capilla' => $faker->company . ' Chapel',
        'descripcion' => $faker->sentence,
        'creado_en' => now(),
    ];
});

$factory->define(Evento::class, function (Faker $faker) {
    $inicio = $faker->dateTimeBetween('+1 days', '+1 month');

    return [
        'titulo' => $faker->sentence(4),
        'descripcion' => $faker->paragraph,
        'fecha_inicio' => $inicio,
        'fecha_fin' => $faker->dateTimeBetween($inicio, '+2 months'),
        'lugar' => $faker->address,
        'hora' => $inicio->format('H:i:s'), // hora separada
        'imagen_url' => $faker->imageUrl(800, 600, 'church', true),
        'creado_por' => factory(Usuario::class),
        'creado_en' => now(),
    ];
});
$factory->define(Noticia::class, function (Faker $faker) {
    return [
        'titulo' => $faker->sentence(6),
        'cuerpo' => $faker->paragraphs(3, true),
        'fecha_publicacion' => now(),
        'autor_id' => factory(Usuario::class),
        'archivo_url' => $faker->optional()->url,
    ];
});
$factory->define(Suscripcion::class, function (Faker $faker) {
    return [
        'correo' => $faker->unique()->safeEmail,
        'tipo' => $faker->randomElement(['eventos', 'misa', 'newsletter']),
        'creado_en' => now(),
    ];
});
$factory->define(Contacto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'correo' => $faker->safeEmail,
        'asunto' => $faker->sentence(5),
        'mensaje' => $faker->paragraph(2),
        'leido' => $faker->boolean(30),
        'recibido_en' => now(),
    ];
});
$factory->define(GaleriaImagen::class, function (Faker $faker) {
    return [
        'titulo' => $faker->sentence(3),
        'descripcion' => $faker->optional()->paragraph,
        'url' => $faker->imageUrl(1024, 768, 'religion', true),
        'subido_por' => factory(Usuario::class),
        'fecha_subida' => now(),
    ];
});