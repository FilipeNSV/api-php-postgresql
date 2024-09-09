<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

/**
 * Middleware for handling JWT authentication.
 */
class ProtectedRoute
{
    /**
     * Loads environment variables and authenticates the JWT token from the request header.
     * Responds with HTTP 401 status code for authentication errors.
     * 
     * @return void
     */
    public static function authenticateJWT(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        // Check if the token is present in the request header
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        $token = $authorization ? str_replace('Bearer ', '', $authorization) : null;

        if (!$token) {
            ResponseHelper::response([
                "status" => "error",
                "message" => "Token not found!",
            ], 401);
        }

        try {
            // Decode the JWT token
            $key = new Key($_ENV['JWT_KEY'], 'HS256');
            $decoded = JWT::decode($token, $key);

            // Check if decoded result is valid
            if (!is_object($decoded)) {
                ResponseHelper::response([
                    "status" => "error",
                    "message" => "Invalid token!",
                ], 401);
            }
        } catch (Throwable $e) {
            // Handle specific JWT errors
            switch ($e->getMessage()) {
                case 'Expired token':
                    ResponseHelper::response([
                        "status" => "error",
                        "message" => "Token expired!",
                    ], 401);
                    break;
                case 'Signature verification failed':
                    ResponseHelper::response([
                        "status" => "error",
                        "message" => "Invalid token signature!",
                    ], 401);
                    break;
                default:
                    ResponseHelper::response([
                        "status" => "error",
                        "message" => $e->getMessage(),
                    ], 401);
                    break;
            }
        }
    }
}
