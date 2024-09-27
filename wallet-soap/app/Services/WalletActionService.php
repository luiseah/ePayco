<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\WalletWithInsufficientFundsException;
use App\Models\Transaction;
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
     * @param $attributes
     * @return Transaction
     * @throws WalletWithInsufficientFundsException
     */
    public function createPaymentIntent($attributes): \App\Models\Transaction
    {
        $amount = $attributes['amount'];

        if($this->wallet->balance < $amount) {
            throw new WalletWithInsufficientFundsException();
        }

        $transaction = $this->wallet->transactions()
            ->create([
                'amount' => $amount,
                'session_id' => Session::getId(),
                'token' => fake()->unique()->randomNumber(6, true),
                'type' => TransactionTypeEnum::Debit,
                'status' => TransactionStatusEnum::Pending,
                'expires_at' => now()->addMinutes(10),
            ]);
        $transaction->refresh();

        $this->wallet->customer->tokenNotify($transaction);

        return $transaction;
    }
}