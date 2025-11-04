<?php

namespace App\Helpers;

class GeneralResponse
{
    public string $status;
    public string $message;
    public object $data;

    /**
     * Constructor
     *
     * @param string $status
     * @param string $message
     * @param object|null $data
     */
    public function __construct(string $status, string $message, ?object $data = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data ?? (object) [];
    }

    /**
     * Convertir a array (útil para JSON response)
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Convertir a JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}