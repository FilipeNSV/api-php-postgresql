<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Helpers\ResponseHelper;
use App\Helpers\Validations;

/**
 * UserController class.
 *
 * Handles user-related requests.
 */
class UserController
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    /**
     * List all users.
     *
     * @return void
     */
    public function listUsers(): void
    {
        $response = $this->userService->listUsers();
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }

    /**
     * Get a specific user by ID.
     *
     * @param int $id
     * @return void
     */
    public function getUser(int $id): void
    {
        if ($id <= 0) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "É necessário passar um ID de usuário válido. Ex.: /user-get/1"
            ], 400);
            return;
        }

        $response = $this->userService->getUser($id);
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }

    /**
     * Create a new user.
     *
     * @param array $request
     * @return void
     */
    public function createUser(array $request): void
    {
        $fields = [
            'name' => 'Nome|required|string',
            'email' => 'Email|required|email',
            'password' => 'Senha|required|password',
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        $sanitizedRequest = [
            'name' => htmlspecialchars(trim($request['name']), ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars(trim($request['email']), ENT_QUOTES, 'UTF-8'),
            'password' => password_hash(trim($request['password']), PASSWORD_DEFAULT)
        ];

        $response = $this->userService->createUser($sanitizedRequest);
        ResponseHelper::response($response, $response['status'] === 'success' ? 201 : 500);
    }

    /**
     * Update an existing user.
     *
     * @param array $request
     * @return void
     */
    public function updateUser(array $request): void
    {
        if (!isset($request['id']) || empty($request['id']) || !is_numeric($request['id'])) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "O ID do usuário é obrigatório para atualização!"
            ], 400);
            return;
        }

        $fields = [
            'name' => 'Nome|string',
            'email' => 'Email|email',
            'password' => 'Senha|password',
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        // Prepare data for update
        $sanitizedRequest = [
            'id' => intval($request['id']),
            'name' => isset($request['name']) ? htmlspecialchars(trim($request['name']), ENT_QUOTES, 'UTF-8') : null,
            'email' => isset($request['email']) ? htmlspecialchars(trim($request['email']), ENT_QUOTES, 'UTF-8') : null,
            'password' => isset($request['password']) ? password_hash(trim($request['password']), PASSWORD_DEFAULT) : null,
        ];

        $response = $this->userService->updateUser(array_filter($sanitizedRequest));
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 500);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        if ($id <= 0) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "É necessário passar um ID de usuário válido."
            ], 400);
            return;
        }

        $response = $this->userService->deleteUser($id);
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }
}
