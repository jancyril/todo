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
        ];

        parent::__construct(array_merge($content, $data), $status, $headers);
    }
}
