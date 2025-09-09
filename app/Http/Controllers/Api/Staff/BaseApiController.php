<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class BaseApiController extends Controller
{
    /**
     * Success response method.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success($data = null, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Error response method.
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    protected function error(string $message = 'Error', int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Handle validation errors.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return JsonResponse
     */
    protected function validationError($validator): JsonResponse
    {
        return $this->error(
            'Validation Error',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()->toArray()
        );
    }

    /**
     * Handle not found error.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Handle unauthorized error.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Handle server error.
     *
     * @param \Throwable $e
     * @param string $message
     * @return JsonResponse
     */
    protected function serverError(\Throwable $e, string $message = 'Internal Server Error'): JsonResponse
    {
        Log::error($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        if (config('app.debug')) {
            return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Handle pagination response.
     *
     * @param mixed $paginatedData
     * @return array
     */
    protected function paginatedResponse($paginatedData): array
    {
        return [
            'data' => $paginatedData->items(),
            'pagination' => [
                'total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage(),
                'from' => $paginatedData->firstItem(),
                'to' => $paginatedData->lastItem(),
            ]
        ];
    }
}
