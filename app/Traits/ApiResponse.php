<?php

namespace App\Traits;

trait ApiResponse
{
    // Format untuk response sukses
    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'  => 'Success',
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    // Format untuk response error
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status'  => 'Error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }
}