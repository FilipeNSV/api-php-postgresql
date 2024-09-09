<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class ProductType
{
    /**
     * Função/Método responsável por listar todos os tipos de produto.
     * 
     * @return array
     */
    public static function listProductTypes(): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT * FROM product_type WHERE deleted_at IS NULL");
            $stmt->execute();

            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                "status" => $list ? "success" : "error",
                "data" => $list,
                "message" => $list ? null : "Nenhum Tipo de Produto encontrado."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro na listagem de Tipos de Produto: " . $e->getMessage()
            ];
        }
    }

    /**
     * Função/Método responsável por criar um novo tipo de produto com base nos dados fornecidos.
     * 
     * @param array $request Os dados do tipo de produto a serem inseridos.
     * @return array
     */
    public static function createProductType(array $request): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("INSERT INTO product_type (name, tax, created_at) VALUES (:name, :tax, NOW())");

            $stmt->bindParam(":name", $request['name']);
            $stmt->bindParam(":tax", $request['tax']);

            $stmt->execute();

            return [
                "status" => "success",
                "message" => "Tipo de produto inserido com sucesso.",
                "productType_id" => $pdo->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Função/Método responsável por deletar um tipo de produto específico.
     * 
     * @param int $id O ID do tipo de produto a ser excluído.
     * @return array
     */
    public static function deleteProductType(int $id): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("UPDATE product_type SET deleted_at = NOW() WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? [
                "status" => "success",
                "message" => "Tipo de Produto excluído com sucesso."
            ] : [
                "status" => "error",
                "message" => "Tipo de Produto não encontrado ou já excluído."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Erro ao excluir o Tipo de Produto: " . $e->getMessage()
            ];
        }
    }
}
