<?php

namespace App\Facades;

use App\Services\TransactionService;
use Illuminate\Support\Facades\Facade;

class Transaction extends Facade
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
        return TransactionService::class;
    }
}
