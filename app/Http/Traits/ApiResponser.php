<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    public function respond($data = '', String $message = 'success', Int $code = 200): JsonResponse
    {
        if (ob_get_length() > 0) ob_end_clean();

        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}