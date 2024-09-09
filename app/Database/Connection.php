<?php

namespace App\Database;

use PDO;
use PDOException;
use Dotenv\Dotenv;

/**
 * Class Connection
 * Provides methods to establish a database connection using PDO.
 */
class Connection
{
    /**
     * Establishes a connection to the database using the provided environment variables.
     *
     * @return PDO The PDO instance representing the connection to the database.
     *
     * @throws PDOException If the connection to the database fails.
     */
    public static function getConnection(): PDO
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // Retrieve database connection details from environment variables
        $dbConnection = $_ENV['DB_CONNECTION'] ?? 'pgsql';
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $dbname = $_ENV['DB_DATABASE'] ?? 'database';
        $username = $_ENV['DB_USERNAME'] ?? 'postgres';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        try {
            // Create a new PDO instance
            $pdo = new PDO("$dbConnection:host=$host;port=$port;dbname=$dbname", $username, $password);

            // Set PDO attributes
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Return the PDO instance
            return $pdo;
        } catch (PDOException $e) {
            // Handle connection errors
            error_log("Database connection error: " . $e->getMessage()); // Log the error for debugging
            throw new PDOException("Unable to connect to the database."); // Re-throw with a generic message
        }
    }
}
