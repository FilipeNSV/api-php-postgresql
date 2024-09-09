<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    /**
     * Handle a purchase transaction.
     *
     * @param array $data
     * @return array
     */
    public function purchaseTransaction(array $data): array
    {
        return Transaction::purchaseTransaction($data);
    }

    /**
     * Handle a sales transaction.
     *
     * @param array $data
     * @return array
     */
    public function salesTransaction(array $data): array
    {
        return Transaction::salesTransaction($data);
    }

    /**
     * List all transactions.
     *
     * @return array
     */
    public function listTransactions(): array
    {
        return Transaction::listTransaction();
    }
}
