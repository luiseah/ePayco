<?php

namespace App\Services;

use App\Facades\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Session;

/**
 * CustomerService
 *
 * This class handles customer-related operations, such as validation and registration.
 * It provides a common structure for managing customer data and returning responses
 * for SOAP services.
 */
class WalletActionService
{
    public function __construct(protected Wallet $wallet)
    {
    }

    /**
     * @param $amount
     * @return Wallet
     */
    public function credit($amount)
    {
        $this->wallet->balance += $amount;
        $this->wallet->save();

        return $this->wallet->refresh();
    }

    /**
     * @param $amount
     * @return Wallet
     */
    public function debit($amount)
    {
        $this->wallet->balance -= $amount;
        $this->wallet->save();

        return $this->wallet->refresh();
    }

    /**
     * @return Wallet
     */
    public function createPaymentIntent(): Wallet
    {
        $transaction = $this->wallet->transactions()
            ->create([
                'amount' => 0,
                'session_id' => Session::getId(),
                'token' => fake()->unique()->randomNumber(6),
                'type' => 'payment_intent',
                'status' => 'pending',
            ]);

        $this->wallet->customer->tokenNotify($transaction);

        return $this->wallet;
    }
}