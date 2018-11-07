<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ErrorResponse extends Response
{
    public function __construct(string $message, array $data = [], int $status = 500, array $headers = [])
    {
        $content = [
            'success' => false,
            'type' => 'error',
            'title' => 'Error',
            'message' => $message,
            'data' => $data,
        ];

        parent::__construct($content, $status, $headers);
    }
}
