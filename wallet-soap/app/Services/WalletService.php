<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Exceptions\IncompleteCustomerAttributesException;
use App\Exceptions\IncompleteWalletAttributesException;
use App\Exceptions\WalletWithInsufficientFundsException;
use App\Facades\Customer;
use App\Facades\Transaction;
use App\Models\Customer as CustomerM;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;

/**
 * CustomerService
 *
 * This class handles customer-related operations, such as validation and registration.
 * It provides a common structure for managing customer data and returning responses
 * for SOAP services.
 */
class WalletService
{
    /**
     * @param array $attributes
     * @return void
     * @throws IncompleteCustomerAttributesException
     */
    private function validateFields(array $attributes)
    {
        $rules = Customer::rules($attributes);

        $rules['amount'] = [
            'required',
            'integer',
            'min:1'
        ];

        // Fields validation
        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            throw new IncompleteCustomerAttributesException($validator);
        }
    }

    /**
     * @param array $attributes
     * @return \App\Services\ApiResponse The API response indicating the recharge status.
     */
    public function recharge(array $attributes) : \App\Services\ApiResponse
    {
        try {
            $this->validateFields($attributes);

            $customer = Customer::find($attributes);

            $wallet = $customer->services()->credit($attributes['amount']);

            return \Api::response(true, '00', 'Recharge processed successfully', [
                'wallet' => $wallet->toArray()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }

    /**
     * @param CustomerM $customer
     * @return Wallet
     */
    public function create(CustomerM $customer)
    {
        return $customer->wallet()->create();
    }

    /**
     * Process a payment.
     *
     * @param array $attributes
     * @return \App\Services\ApiResponse
     */
    public function payment(array $attributes) : \App\Services\ApiResponse
    {
        try {
            $this->validateFields($attributes);

            $customer = Customer::find($attributes);

            $amount = $attributes['amount'];

            if ($customer->wallet->balance < $amount) {
                throw new WalletWithInsufficientFundsException();
            }

            $transaction = $customer->services()->createPaymentIntent($attributes);

            return \Api::response(true, '00', __('Payment processed successfully.'), [
                'transaction' => $transaction->toArray()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        } catch (WalletWithInsufficientFundsException $e) {
            return \Api::response(false, $e->getCode(), $e->getMessage(), []);
        }
    }

    /**
     * @param array $attributes
     * @return \App\Services\ApiResponse
     */
    public function paymentConfirmation(array $attributes) : \App\Services\ApiResponse
    {
        try {
            $transaction = Transaction::find($attributes);

            $wallet = $transaction->wallet->actions()->debit($transaction->amount);

            $transaction->update([
                'status' => TransactionStatusEnum::Confirmed,
                'confirmed_at' => now(),
                'token' => '000000'
            ]);

            return \Api::response(true, '00', __('Payment confirmation processed successfully.'), [
                'wallet' => $wallet->toArray(),
                'transaction' => $transaction->toArray()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }

    /**
     * @param array $attributes
     * @return void
     * @throws IncompleteWalletAttributesException
     */
    private function validateFieldsBalace(array $attributes)
    {
        $rules = Customer::rules($attributes);

        // Fields validation
        $validator = Validator::make($attributes, $rules);

        if ($validator->fails()) {
            throw new IncompleteWalletAttributesException($validator);
        }
    }

    /**
     * @param array $attributes
     * @return ApiResponse
     * @throws IncompleteWalletAttributesException
     */
    public function balanceInquiry(array $attributes)
    {
        try {
            $this->validateFieldsBalace($attributes);

            $customer = Customer::find($attributes);

            $wallet = $customer->wallet;

            return \Api::response(true, '00', __('Balance inquiry processed successfully.'), [
                'wallet' => $wallet->getKey()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }
}