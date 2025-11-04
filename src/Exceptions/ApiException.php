<?php

declare(strict_types=1);

namespace Blog\Exceptions;

use Exception;

/**
 * API Exception for handling HTTP status code responses
 */
class ApiException extends Exception
{
    private int $statusCode;
    private array $errors;
    
    /**
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param array $errors Additional error details
     */
    public function __construct(string $message, int $statusCode = 500, array $errors = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }
    
    /**
     * Get the HTTP status code
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Get additional error details
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
