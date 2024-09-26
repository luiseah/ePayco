<?php

namespace App\Exceptions;

use Exception;

class WalletWithInsufficientFundsException extends Exception
{
    protected $code = 400;
    protected $message = 'Wallet with insufficient funds';
}
