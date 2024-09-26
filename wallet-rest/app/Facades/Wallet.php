<?php

namespace App\Facades;

use App\Services\WalletService;
use Illuminate\Support\Facades\Facade;

class Wallet extends Facade
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
        return 'wallet';
    }
}
