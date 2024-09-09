<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Helpers\ResponseHelper;
use App\Helpers\Validations;

/**
 * TransactionController class.
 *
 * Handles transaction-related requests.
 */
class TransactionController
{
    protected $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Handle a purchase transaction.
     *
     * @param array $request
     * @return void
     */
    public function purchaseTransaction(array $request): void
    {
        $fields = [
            'supplier_name' => 'Nome do fornecedor|required|string',
            'value_without_tax' => 'Valor sem taxa|required|numeric',
            'total_tax' => 'Total de taxas|required|numeric',
            'product_id' => 'ID do produto|required|int',
            'amount' => 'Quantidade|required|int',
            'total_value' => 'Valor total|required|numeric'
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        $response = $this->transactionService->purchaseTransaction($request);
        ResponseHelper::response($response, $response['status'] === 'success' ? 201 : 500);
    }

    /**
     * Handle a sales transaction.
     *
     * @param array $request
     * @return void
     */
    public function salesTransaction(array $request): void
    {
        $fields = [
            'customer_name' => 'Nome do cliente|required|string',
            'product_id' => 'ID do produto|required|int',
            'amount' => 'Quantidade|required|int',
            'total_value' => 'Valor total|required|numeric'
        ];

        $missingFields = Validations::checkFields($request, $fields);

        if (!empty($missingFields)) {
            ResponseHelper::response([
                "status" => 'error',
                "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)
            ], 400);
            return;
        }

        $response = $this->transactionService->salesTransaction($request);
        ResponseHelper::response($response, $response['status'] === 'success' ? 201 : 500);
    }

    /**
     * List all transactions.
     *
     * @return void
     */
    public function listTransaction(): void
    {
        $response = $this->transactionService->listTransactions();
        ResponseHelper::response($response, $response['status'] === 'success' ? 200 : 404);
    }
}
