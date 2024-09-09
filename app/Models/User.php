<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class User
{
    /**
     * List all users.
     *
     * @return array
     */
    public static function listUsers(): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT id, name, email, created_at, updated_at FROM users WHERE deleted_at IS NULL");

            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => $users ? "success" : "error",
                "data" => $users,
                "message" => $users ? null : "Nenhum usuário encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na listagem de usuários: " . $e->getMessage()
            ];
        }
    }

    /**
     * Get a specific user by ID.
     *
     * @param int $id
     * @return array
     */
    public static function getUser(int $id): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? [
                "status" => "success",
                "data" => $user
            ] : [
                "status" => "error",
                "message" => "Usuário não encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao buscar o usuário: " . $e->getMessage()
            ];
        }
    }

    /**
     * Get a specific user by ID.
     *
     * @param string $email
     * @return array
     */
    public static function getUserByEmail(string $email): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? [
                "status" => "success",
                "data" => $user
            ] : [
                "status" => "error",
                "message" => "Usuário não encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao buscar o usuário: " . $e->getMessage()
            ];
        }
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return array
     */
    public static function createUser(array $data): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())");
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":password", $data['password']);
            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Usuário inserido com sucesso.",
                "user_id" => $pdo->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na inserção do usuário: " . $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing user.
     *
     * @param array $data
     * @return array
     */
    public static function updateUser(array $data): array
    {
        try {
            $id = $data['id'];
            $fields = [];
            $params = [':id' => $id];

            if (isset($data['name'])) {
                $fields[] = "name = :name";
                $params[':name'] = $data['name'];
            }

            if (isset($data['email'])) {
                $fields[] = "email = :email";
                $params[':email'] = $data['email'];
            }

            if (isset($data['password'])) {
                $fields[] = "password = :password";
                $params[':password'] = $data['password'];
            }

            if (empty($fields)) {
                return [
                    "status" => "error",
                    "message" => "Nenhum dado para atualizar."
                ];
            }

            $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
            $pdo = Connection::getConnection();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount() > 0 ? [
                "status" => "success",
                "message" => "Usuário atualizado com sucesso."
            ] : [
                "status" => "error",
                "message" => "Nenhuma atualização realizada. Verifique o ID e os dados fornecidos."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na atualização do usuário: " . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @param bool $permanently - optional
     * @return array
     */
    public static function deleteUser(int $id, bool $permanently = false): array
    {
        try {
            $pdo = Connection::getConnection();

            if ($permanently) {
                $user = self::getUser($id);
                if ($user['data']['email'] === 'emailtest@test.com') {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
                }                
            } else {
                $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
            }

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? [
                "status" => "success",
                "message" => $permanently ? "Usuário excluído permanentemente com sucesso." : "Usuário excluído com sucesso."
            ] : [
                "status" => "error",
                "message" => "Usuário não encontrado ou já excluído."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao excluir o usuário: " . $e->getMessage()
            ];
        }
    }
}
