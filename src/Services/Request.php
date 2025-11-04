<?php

declare(strict_types=1);

namespace Blog\Services;

/**
 * Request handler for API requests
 */
class Request
{
    /**
     * Get the request method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Check if request is a specific method
     *
     * @param string $method HTTP method to check against
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->getMethod() === strtoupper($method);
    }
    
    /**
     * Get query parameters
     *
     * @param string|null $key Parameter name
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public function query(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Get JSON body content as associative array
     *
     * @return array
     */
    public function getJsonBody(): array
    {
        $content = file_get_contents('php://input');
        
        if (empty($content)) {
            return [];
        }
        
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        
        return $data;
    }
    
    /**
     * Get URL parameter by index
     *
     * @param int $index Parameter position
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function getUrlParam(int $index, $default = null)
    {
        $params = $this->getUrlParams();
        
        return $params[$index] ?? $default;
    }
    
    /**
     * Get all URL parameters
     *
     * @return array
     */
    public function getUrlParams(): array
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        // Remove the base path segment (if any)
        $basePath = '/api';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Split the path into segments and filter out empty values
        $segments = array_filter(explode('/', $path));
        
        // Re-index the array
        return array_values($segments);
    }
}
