<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;
use App\Http\Middleware\HeaderApiMiddleware;

// Middleware for API headers
$headerMiddleware = new HeaderApiMiddleware();
$headerMiddleware->handle();

// Load routes configuration
require_once __DIR__ . '/../routes/api.php';

// Initialize Router
$router = new Router($routes, $protectedRoutes);
$router->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
