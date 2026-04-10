<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function json(string $message = null, $data = [], $statusCode = 200, array $headers = [])
    {
        $content = [];

        if ($message) {
            $content['message'] = $message;
        }

        if (!empty($data) || $data == null) {
            $content['data'] = $data;
        }

        return response()->json($content, $statusCode, $headers, JSON_PRESERVE_ZERO_FRACTION);
    }
}
