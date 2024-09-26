<?php

namespace Tests\Managers;


use App\Models\Customer;

trait CustomerManager
{
    /**
     * @param array $attributes
     * @return Customer
     */
    public function createCustomer(array $attributes = []): Customer
    {
        return Customer::factory()
            ->create($attributes);
    }
}