<?php

namespace App\Services;

use App\Exceptions\IncompleteCustomerAttributesException;
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
     * @return \App\Services\Responses\ApiResponse The API response indicating the recharge status.
     */
    public function recharge(array $attributes)
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
     * @param array $attributes
     * @return Responses\ApiResponse
     */
    public function payment(array $attributes)
    {
        try {
            $this->validateFields($attributes);

            $customer = Customer::find($attributes);

            $wallet = $customer->services()->createPaymentIntent();

            return \Api::response(true, '00', 'Recharge processed successfully', [
                'wallet' => $wallet->toArray()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }

    /**
     * @param array $attributes
     * @return Responses\ApiResponse
     */
    public function paymentConfirmation(array $attributes)
    {
        try {
            $transaction = Transaction::find($attributes);

            $wallet = $transaction->wallet->actions()->debit($transaction->amount);

            return \Api::response(true, '00', 'Recharge processed successfully', [
                'wallet' => $wallet->toArray()
            ]);
        } catch (IncompleteCustomerAttributesException $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }
}