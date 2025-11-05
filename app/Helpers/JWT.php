<?php

namespace App\Helpers;

class JWT
{
    /**
     * Obtener la clave secreta desde .env
     */
    private static function getSecret(): string
    {
        return env('SECRET_KEY', 'clave_por_defecto'); // fallback
    }

    /**
     * Recupera el tiempo de expiración desde .env (en segundos)
     */
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

        $base64UrlHeader  = self::base64UrlEncode(json_encode($header));
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
        $signature        = self::sign("$base64UrlHeader.$base64UrlPayload", self::getSecret());

        return "$base64UrlHeader.$base64UrlPayload.$signature";
    }

    /**
     * Decodificar JWT y validar firma + expiración
     *
     * @param string $token
     * @return array|null Devuelve el payload o null si no es válido
     */
    public static function decode(string $token): ?array
{
    try {
        // Separar header, payload y firma
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header64, $payload64, $signature] = $parts;

        // Decodificar payload
        $payload = json_decode(base64_decode($payload64), true);
        if (!$payload) {
            return null;
        }

        // verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        // verificar firma
        $expectedSig = self::sign("$header64.$payload64", self::getSecret());
        if (!hash_equals($expectedSig, $signature)) {
            return null;
        }

        // todo correcto
        return $payload;

    } catch (\Throwable $e) {
        \Log::error('Error al decodificar JWT', [
            'token' => $token,
            'exception' => $e->getMessage()
        ]);
        return null;
    }
}


    /**
     * Firmar el token (HMAC SHA256)
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
