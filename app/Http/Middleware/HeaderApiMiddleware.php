<?php

namespace App\Http\Middleware;

class HeaderApiMiddleware
{
    /**
     * Sets the appropriate headers for CORS (Cross-Origin Resource Sharing) and JSON responses.
     * This method handles preflight OPTIONS requests and regular requests, setting headers to allow
     * cross-origin requests, specify allowed methods, and support custom headers.
     * 
     * @return void
     */
    public function handle(): void
    {
        // Check if the request is an OPTIONS request (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *"); // Adjust this to the allowed domain if necessary
            // header("Access-Control-Allow-Origin: https://nsvdev.com.br"); // Uncomment and set domain for production
            header("Access-Control-Allow-Methods: GET, POST");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Expose-Headers: X-Custom-Header");
            exit;
        }

        // Set headers for all other requests
        header("Access-Control-Allow-Origin: *"); // Adjust this to the allowed domain if necessary
        // header("Access-Control-Allow-Origin: https://nsvdev.com.br"); // Uncomment and set domain for production
        header("Content-Type: application/json");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization");
    }
}
