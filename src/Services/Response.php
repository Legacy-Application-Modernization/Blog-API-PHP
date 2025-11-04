<?php

declare(strict_types=1);

namespace Blog\Services;

/**
 * Response handler for API responses
 */
class Response
{
    /**
     * Send a JSON response with appropriate status code
     *
     * @param mixed $data Response data
     * @param int $statusCode HTTP status code
     * @return void
     */
    public static function json($data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send a success response
     *
     * @param mixed $data Response data
     * @param int $statusCode HTTP status code
     * @return void
     */
    public static function success($data = null, int $statusCode = 200): void
    {
        $response = [
            'success' => true,
            'data' => $data
        ];
        
        self::json($response, $statusCode);
    }
    
    /**
     * Send an error response
     *
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param array $errors Additional error details
     * @return void
     */
    public static function error(string $message, int $statusCode = 500, array $errors = []): void
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        self::json($response, $statusCode);
    }
}
