<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GeneralJsonException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report(Request $request)
    {
        $data = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'class' => get_class($this),
            'request_id' => $request->header('X-Request-Id') ?? uniqid(),
            'datetime' => date('Y-m-d H:i:s'),
            // 'trace' => $this->getTrace()
        ];

        switch ($this->getCode()) {
            case 400:
                Log::info('BadRequest', $data);
                break;

            case 401:
                Log::warning('Unauthorized', $data);
                break;

            case 403:
                Log::warning('Forbidden', $data);
                break;

            case 404:
                Log::warning('NotFound', $data);
                break;

            case 422:
                Log::warning('UnprocessableEntity', $data);
                break;

            case 500:
                Log::error('InternalServerError', $data);
                break;
            case 23000:
                Log::error('DatabaseError', $data);
                break;
            case 600:
                Log::error('CustomError', $data);
                break;

            case 700:
                Log::warning('CustomWarning', $data);
                break;

            default:
                Log::error('UnhandledException', $data);
        }
    }

    public function render(Request $request): JsonResponse
    {
        $data = [
            'error' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'datetime' => date('Y-m-d H:i:s'),
                'request_id' => $request->header('X-Request-Id') ?? uniqid(),
                // 'trace' => $this->getTrace(),
            ]
        ];


        if ($request->expectsJson()) {
            return response()->json($data, 500);
        }

        return response()->json(['message' => $this->getMessage()], $this->getCode());
    }
}
