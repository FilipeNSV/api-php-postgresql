<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Retrieve a list of all users.
     *
     * @return array
     */
    public function listUsers(): array
    {
        return User::listUsers();
    }

    /**
     * Retrieve a specific user by ID.
     *
     * @param int $id
     * @return array
     */
    public function getUser(int $id): array
    {
        return User::getUser($id);
    }

    /**
     * Create a new user with the given data.
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data): array
    {
        return User::createUser($data);
    }

    /**
     * Update an existing user with the given data.
     *
     * @param array $data
     * @return array
     */
    public function updateUser(array $data): array
    {
        return User::updateUser($data);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return array
     */
    public function deleteUser(int $id): array
    {
        return User::deleteUser($id);
    }
}
