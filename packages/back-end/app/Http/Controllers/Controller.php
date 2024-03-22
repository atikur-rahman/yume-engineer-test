<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Send an error response.
     *
     * @param Throwable $err The exception that occurred.
     * @return JsonResponse The JSON response.
     */
    protected function sendErrorResponse(Throwable $err): JsonResponse
    {
        //todo: properly handle exception, ex: send this error to sentry
        Log::error($err->getMessage());
        Log::error($err->getTraceAsString());

        $message = env("APP_DEBUG") === true ? $err->getMessage() : "Server Error";

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 500);
    }


    /**
     * Sends a success response with a message, status code, and optional payload.
     *
     * @param string $message The message to include in the response
     * @param int $statusCode The status code for the response (default is 200)
     * @param array $payload Additional data to include in the response (default is an empty array)
     * @return JsonResponse The JSON response containing the message, success flag, and data
     */
    protected function sendSuccessResponse(string $message, int $statusCode = 200, array $payload = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'success' => 1,
            'data' => $payload
        ], $statusCode);
    }
}
