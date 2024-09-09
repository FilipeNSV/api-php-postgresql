<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class Product
{
    /**
     * Method responsible for listing all products from the product table.
     * 
     * @return array
     */
    public static function listProducts(): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT product.id, product.name, product.product_type_id, product.description, product.value, product.created_at, 
                            product_type.name AS product_type_name, product_type.tax AS tax
                            FROM product JOIN product_type ON product.product_type_id = product_type.id
                            WHERE product.deleted_at IS NULL");

            $stmt->execute();

            $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => $product ? "success" : "error",
                "data" => $product,
                "message" => $product ? null : "Nenhum Produto encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na listagem de Produtos: " . $e->getMessage()
            ];
        }
    }

    /**
     * Method responsible for retrieving a specific product.
     * 
     * @param int $id The ID of the product to be retrieved.
     * @return array
     */
    public static function getProduct(int $id): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            return $product ? [
                "status" => "success",
                "data" => $product
            ] : [
                "status" => "error",
                "message" => "Produto não encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao buscar o Produto: " . $e->getMessage()
            ];
        }
    }

    /**
     * Method responsible for creating a new product based on the provided data.
     * 
     * @param array $request The product data to be inserted.
     * @return array The ID of the newly created product.
     */
    public static function createProduct(array $request): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("INSERT INTO product (name, description, product_type_id, value, created_at) VALUES (:name, :description, :product_type_id, :value, NOW())");

            $stmt->bindParam(":name", $request['name']);
            $stmt->bindParam(":description", $request['description']);
            $stmt->bindParam(":product_type_id", $request['product_type_id']);
            $stmt->bindParam(":value", $request['value']);

            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Produto inserido com sucesso.",
                "user_id" => $pdo->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na inserção do Produto: " . $e->getMessage()
            ];
        }
    }

    /**
     * Method responsible for updating a product based on the provided data.
     * 
     * @param array $request The product data to be updated.
     * @return array The number of affected rows.
     */
    public static function updateProduct(array $request): array
    {
        try {
            $id = $request['id'];
            $name = $request['name'] ?? null;
            $description = $request['description'] ?? null;
            $product_type_id = $request['product_type_id'] ?? null;
            $value = $request['value'] ?? null;

            $pdo = Connection::getConnection();

            // Build the SQL update query
            $sql = "UPDATE product SET updated_at = NOW()";

            // Check and add the fields to be updated
            $params = [];

            if (!empty($name)) {
                $sql .= ", name = :name";
                $params[':name'] = $name;
            }

            if (!empty($description)) {
                $sql .= ", description = :description";
                $params[':description'] = $description;
            }

            if (!empty($product_type_id)) {
                $sql .= ", product_type_id = :product_type_id";
                $params[':product_type_id'] = $product_type_id;
            }

            if (!empty($value)) {
                $sql .= ", value = :value";
                $params[':value'] = $value;
            }

            $sql .= " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount() > 0 ? [
                "status" => "success",
                "message" => "Produto atualizado com sucesso."
            ] : [
                "status" => "error",
                "message" => "Nenhuma atualização realizada. Verifique o ID e os dados fornecidos."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na atualização do Produto: " . $e->getMessage()
            ];
        }
    }

    /**
     * Method responsible for deleting a specific product.
     * 
     * @param int $id The ID of the product to be deleted.
     * @return array The number of affected rows.
     */
    public static function deleteProduct(int $id): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("UPDATE product SET deleted_at = NOW() WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? [
                "status" => "success",
                "message" => "Produto excluído com sucesso."
            ] : [
                "status" => "error",
                "message" => "Produto não encontrado ou já excluído."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao excluir o Produto: " . $e->getMessage()
            ];
        }
    }
}
