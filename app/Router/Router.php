<?php

namespace App\Router;

use App\Http\Middleware\ProtectedRoute;
use App\Helpers\ResponseHelper;

/**
 * Class Router
 * Handles routing of HTTP requests to controllers and actions.
 *
 * @property array<string, array<string, string>> $routes Array of routes mapped to controller actions.
 * @property array<string> $protectedRoutes Array of protected routes requiring authentication.
 */
class Router
{
    /**
     * @var array<string, array<string, string>> Array of routes mapped to controller actions.
     */
    private array $routes = [];

    /**
     * @var array<string> Array of protected routes requiring authentication.
     */
    private array $protectedRoutes = [];

    /**
     * Router constructor.
     *
     * @param array<string, array<string, string>> $routes Array of routes mapped to controller actions.
     * @param array<string> $protectedRoutes Array of protected routes requiring authentication.
     */
    public function __construct(array $routes, array $protectedRoutes)
    {
        $this->routes = $routes;
        $this->protectedRoutes = $protectedRoutes;
    }

    /**
     * Handles the incoming HTTP request.
     *
     * @param string $method HTTP method (GET, POST, etc.).
     * @param string $uri Request URI.
     *
     * @return void
     */
    public function handleRequest(string $method, string $uri): void
    {
        if (!isset($this->routes[$method])) {
            ResponseHelper::response("Method not allowed", 405);
        }

        $path = explode('/', trim($uri, '/'));
        $route = $path[1] ?? null;

        if (!isset($this->routes[$method][$route])) {
            ResponseHelper::response("Route not found!", 404);
        }

        // Check if the route is protected
        if (in_array($route, $this->protectedRoutes)) {
            $protectedRoute = new ProtectedRoute();
            $protectedRoute->authenticateJWT();
        }

        // Handle the request
        list($controllerName, $action) = explode('@', $this->routes[$method][$route]);
        $controllerPath = "App\Http\Controllers\\" . $controllerName;

        if (!class_exists($controllerPath) || !method_exists($controllerPath, $action)) {
            ResponseHelper::response("Controller or method not found!", 500);
        }

        $instance = new $controllerPath;

        // Handle data based on method type
        if ($method === 'GET') {
            // For GET requests, data is usually in query parameters
            $data = isset($path[2]) ? $path[2] : $_GET;
        } else {
            // For other methods, try reading from the body of the request
            $data = (array) json_decode(file_get_contents("php://input"), true);

            // Fallback to $_POST if data from body is empty
            if (empty($data) && $method == 'POST') {
                $data = $_POST;
            }
        }

        try {
            $instance->$action($data);
        } catch (\Throwable $e) {
            ResponseHelper::response($e->getMessage(), 500);
        }
    }
}
