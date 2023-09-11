<?php


namespace App\Services\Api;

class ApiAnswerService
{
    public static function successfulAnswer($status = 200)
    {
        return response()->json(['status' => 'success'], $status);
    }

    public static function successfulAnswerWithData($data, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $status);
    }

    public static function errorAnswer($message, $status = 200)
    {
        return response()->json([
                'status' => 'error',
                'message' => $message
            ], $status);
    }
}
