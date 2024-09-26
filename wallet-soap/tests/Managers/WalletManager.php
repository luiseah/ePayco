<?php

namespace Tests\Managers;


use App\Models\Customer;
use App\Models\Wallet;

trait WalletManager
{
    /**
     * @param Customer $customer
     * @param array $attributes
     * @return Wallet
     */
    public function createWallet(Customer $customer, array $attributes = []): Wallet
    {
        return Wallet::factory()
            ->for($customer, 'customer')
            ->create($attributes);
    }

    public function createWalletWithCustomer(array $attributes = []): array
    {
        return Wallet::factory()
            ->create($attributes);
    }
}