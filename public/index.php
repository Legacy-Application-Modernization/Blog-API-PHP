<?php

declare(strict_types=1);

// Set error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Blog\Controllers\PostController;
use Blog\Exceptions\ApiException;
use Blog\Services\Request;
use Blog\Services\Response;
use Blog\Services\Router;

// Set headers for CORS and JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Create request and router instances
    $request = new Request();
    $router = new Router($request);
    
    // Define routes for blog post API
    $router
        ->get('/posts', function () use ($request) {
            $controller = new PostController($request);
            $controller->index();
        })
        ->get('/posts/{id}', function ($id) use ($request) {
            $controller = new PostController($request);
            $controller->show($id);
        })
        ->post('/posts', function () use ($request) {
            $controller = new PostController($request);
            $controller->store();
        })
        ->put('/posts/{id}', function ($id) use ($request) {
            $controller = new PostController($request);
            $controller->update($id);
        })
        ->delete('/posts/{id}', function ($id) use ($request) {
            $controller = new PostController($request);
            $controller->destroy($id);
        });
    
    // Dispatch the router
    $router->dispatch();
} catch (ApiException $e) {
    // Handle API exceptions
    Response::error(
        $e->getMessage(),
        $e->getStatusCode(),
        $e->getErrors()
    );
} catch (Exception $e) {
    // Handle generic exceptions
    Response::error(
        'An unexpected error occurred: ' . $e->getMessage(),
        500
    );
}
