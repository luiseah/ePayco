<?php

namespace App\Facades;

use App\Services\CustomerService;
use Illuminate\Support\Facades\Facade;

class Customer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return CustomerService::class;
    }
}
