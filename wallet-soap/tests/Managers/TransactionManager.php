<?php

namespace Tests\Managers;


use App\Models\Transaction;
use App\Models\Wallet;

trait TransactionManager
{
    /**
     * @param Wallet $wallet
     * @param array $attributes
     * @return Transaction
     */
    public function createTransaction(Wallet $wallet, array $attributes): Transaction
    {
        return Transaction::factory()
            ->for($wallet)
            ->create($attributes);
    }
}