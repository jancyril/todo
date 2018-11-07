<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class SuccessResponse extends Response
{
    public function __construct(string $message, array $data = [], int $status = 200, array $headers = [])
    {
        $content = [
            'success' => true,
            'type' => 'success',
            'title' => 'Success',
            'message' => $message,
            'data' => $data,
        ];

        parent::__construct($content, $status, $headers);
    }
}
