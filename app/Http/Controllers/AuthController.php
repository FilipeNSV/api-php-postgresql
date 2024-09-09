<?php

namespace App\Http\Controllers;

use App\Database\Connection;
use App\Helpers\ResponseHelper;
use App\Helpers\Validations;
use PDO;
use Dotenv;
use Firebase\JWT\JWT;

/**
 * AuthController class.
 *
 * Handles authentication-related requests.
 */
class AuthController
{
	public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
        $dotenv->load();
    }

	/**
     * Handle user login.
     *
     * @param array $request
     * @return void
     */
	public function login(array $request): void
	{
		$fields = [
			'email' => 'E-mail|required|email',
			'password' => 'Senha|required|password'
		];

		$missingFields = Validations::checkfields($request, $fields);

		if (!empty($missingFields)) {
			http_response_code(400);
			echo json_encode(["status" => 'error', "message" => implode(" ", $missingFields)]);
			return;
		}

		$pdo = Connection::getConnection();
		$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$stmt->bindParam(":email", $request['email'], PDO::PARAM_STR);
		$stmt->execute();

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$user || !password_verify($request['password'], $user['password'])) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Incorrect E-mail or Password."
            ], 404);
            return;
        }

		$payload = [
            "exp" => time() + 3600, // 3600 seconds (1 hour)
            "iat" => time(),
            "email" => $request['email']
        ];

        $token = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        $response = [
            "status" => 'success',
            "token" => $token,
            "name" => $user['name']
        ];

        ResponseHelper::response($response, 200);
	}
}
