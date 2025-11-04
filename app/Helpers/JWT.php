<?php

namespace App\Helpers;

class JWT
{
    private static function getSecret(): string
    {
        return env('SECRET_KEY', 'clave_por_defecto'); // fallback
    }

    // Recupera el tiempo de expiración desde .env
    private static function getExpiration(): int
    {
        return (int) env('EXPIRATION', 60) * 60; // convertir minutos a segundos
    }

    /**
     * Crear JWT
     *
     * @param array $payload
     * @param int $expire Tiempo de expiración en segundos
     * @return string
     */
    public static function create(array $payload, int $expire = 3600): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $expireTime = $expire ?? self::getExpiration();
        $payload['exp'] = time() + $expireTime;

        $base64UrlHeader = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
        $signature = self::sign("$base64UrlHeader.$base64UrlPayload", self::getSecret());

        return "$base64UrlHeader.$base64UrlPayload.$signature";
    }

    /**
     * Firmar el token
     */
    private static function sign(string $data, string $secret): string
    {
        return self::base64UrlEncode(hash_hmac('sha256', $data, $secret, true));
    }

    /**
     * Codificar en Base64 URL-safe
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
