<?php

declare(strict_types=1);

namespace Blog\Services;

use Blog\Exceptions\ApiException;
use Closure;
use Exception;

/**
 * Router for handling API routes
 */
class Router
{
    private array $routes = [];
    private Request $request;
    
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Add a route to the router
     *
     * @param string $method HTTP method
     * @param string $pattern URL pattern
     * @param Closure $handler Route handler
     * @return self
     */
    public function addRoute(string $method, string $pattern, Closure $handler): self
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
        
        return $this;
    }
    
    /**
     * Add a GET route
     *
     * @param string $pattern URL pattern
     * @param Closure $handler Route handler
     * @return self
     */
    public function get(string $pattern, Closure $handler): self
    {
        return $this->addRoute('GET', $pattern, $handler);
    }
    
    /**
     * Add a POST route
     *
     * @param string $pattern URL pattern
     * @param Closure $handler Route handler
     * @return self
     */
    public function post(string $pattern, Closure $handler): self
    {
        return $this->addRoute('POST', $pattern, $handler);
    }
    
    /**
     * Add a PUT route
     *
     * @param string $pattern URL pattern
     * @param Closure $handler Route handler
     * @return self
     */
    public function put(string $pattern, Closure $handler): self
    {
        return $this->addRoute('PUT', $pattern, $handler);
    }
    
    /**
     * Add a DELETE route
     *
     * @param string $pattern URL pattern
     * @param Closure $handler Route handler
     * @return self
     */
    public function delete(string $pattern, Closure $handler): self
    {
        return $this->addRoute('DELETE', $pattern, $handler);
    }
    
    /**
     * Match and execute the appropriate route
     *
     * @return void
     * @throws ApiException
     */
    public function dispatch(): void
    {
        $method = $this->request->getMethod();
        $path = '/' . implode('/', $this->request->getUrlParams());
        
        foreach ($this->routes as $route) {
            // Skip routes with different methods
            if ($route['method'] !== $method) {
                continue;
            }
            
            // Convert route pattern to regex for matching
            $pattern = $this->patternToRegex($route['pattern']);
            
            if (preg_match($pattern, $path, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                try {
                    // Execute the route handler with matched parameters
                    $route['handler'](...$matches);
                    return;
                } catch (ApiException $e) {
                    // Pass API exceptions up
                    throw $e;
                } catch (Exception $e) {
                    // Convert generic exceptions to API exceptions
                    throw new ApiException(
                        $e->getMessage(),
                        500,
                        ['exception' => get_class($e)]
                    );
                }
            }
        }
        
        // No route matched
        throw new ApiException('Route not found', 404);
    }
    
    /**
     * Convert a URL pattern to a regex pattern
     *
     * @param string $pattern URL pattern
     * @return string
     */
    private function patternToRegex(string $pattern): string
    {
        // Replace {param} placeholders with regex capturing groups
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        
        // Escape forward slashes and add start/end anchors
        return '#^' . $regex . '$#';
    }
}
