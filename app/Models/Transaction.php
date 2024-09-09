<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class Transaction
{
    /**
     * Create a new purchase transaction.
     *
     * @param array $request Transaction data to be inserted.
     * @return array Response status and message.
     */
    public static function purchaseTransaction(array $request): array
    {
        try {
            $transactionType = 'Purchase';

            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("INSERT INTO transactions 
                (transaction_type, supplier_name, value_without_tax, total_tax, product_id, amount, total_value, created_at) 
                VALUES (:transaction_type, :supplier_name, :value_without_tax, :total_tax, :product_id, :amount, :total_value, NOW())");

            $stmt->bindParam(":transaction_type", $transactionType);
            $stmt->bindParam(":supplier_name", $request['supplier_name']);
            $stmt->bindParam(":value_without_tax", $request['value_without_tax']);
            $stmt->bindParam(":total_tax", $request['total_tax']);
            $stmt->bindParam(":product_id", $request['product_id']);
            $stmt->bindParam(":amount", $request['amount']);
            $stmt->bindParam(":total_value", $request['total_value']);

            $stmt->execute();

            $newTransaction = $pdo->lastInsertId();

            $response = [
                "status" => "success",
                "message" => "Transaction successfully inserted.",
                "transaction_id" => $newTransaction
            ];

            return $response;
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error inserting transaction: " . $e->getMessage()
            ];
        }
    }

    /**
     * Create a new sales transaction.
     *
     * @param array $request Transaction data to be inserted.
     * @return array Response status and message.
     */
    public static function salesTransaction(array $request): array
    {
        try {
            $transactionType = 'Sale';

            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("INSERT INTO transactions 
                (transaction_type, customer_name, product_id, amount, total_value, created_at) 
                VALUES (:transaction_type, :customer_name, :product_id, :amount, :total_value, NOW())");

            $stmt->bindParam(":transaction_type", $transactionType);
            $stmt->bindParam(":customer_name", $request['customer_name']);
            $stmt->bindParam(":product_id", $request['product_id']);
            $stmt->bindParam(":amount", $request['amount']);
            $stmt->bindParam(":total_value", $request['total_value']);

            $stmt->execute();

            $newTransaction = $pdo->lastInsertId();

            $response = [
                "status" => "success",
                "message" => "Transaction successfully inserted.",
                "transaction_id" => $newTransaction
            ];

            return $response;
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error inserting transaction: " . $e->getMessage()
            ];
        }
    }

    /**
     * List all transactions.
     *
     * @return array Response with transaction data and status.
     */
    public static function listTransaction(): array
    {
        try {
            $pdo = Connection::getConnection();

            $stmt = $pdo->prepare("SELECT transactions.*, product.name AS product_name
                FROM transactions
                JOIN product ON transactions.product_id = product.id");
            $stmt->execute();

            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($transactions) {
                return [
                    "status" => "success",
                    "data" => $transactions
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "No transactions found."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Error listing transactions: " . $e->getMessage()
            ];
        }
    }
}
