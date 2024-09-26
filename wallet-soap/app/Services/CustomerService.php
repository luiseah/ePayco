<?php

namespace App\Services;

use App\Exceptions\IncompleteCustomerAttributesException;
use App\Facades\Wallet;
use App\Models\Customer;
use App\Models\Customer as CustomerM;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * CustomerService
 *
 * This class handles customer-related operations, such as validation and registration.
 * It provides a common structure for managing customer data and returning responses
 * for SOAP services.
 */
class CustomerService
{
    /**
     * @param $attributes
     * @return mixed
     */
    public function find($attributes)
    {
        return Customer::where([
            'document_id' => $attributes['document_id'],
            'phone' => $attributes['phone']
        ])->firstOrFail();
    }

    public function rules($attributes)
    {
        return [
            'document_id' => ['required',
                'string',
                'max:20',
                Rule::exists(CustomerM::class, 'document_id')->where('phone', $attributes['phone'])
            ],
            'phone' => ['required',
                'string',
                'max:15',
                Rule::exists(CustomerM::class, 'phone')->where('document_id', $attributes['document_id'])
            ],
        ];
    }

    /**
     * Validate the fields of the customer.
     *
     * This method validates the customer attributes before performing any operation.
     * It ensures that required fields such as document ID, name, email, and phone are present
     * and that they adhere to the defined validation rules.
     *
     * @param array $attributes Array of customer attributes to validate.
     * @return void
     * @throws IncompleteCustomerAttributesException If validation fails, an exception is thrown with the validation errors.
     */
    private function validateFields(array $attributes)
    {
        // Fields validation
        $validator = Validator::make($attributes, [
            'document_id' => ['required',
                'string',
                'max:20',
                Rule::unique(Customer::class, 'document_id')
            ],
            'name' => ['required',
                'string',
                'max:255'
            ],
            'email' => ['required',
                'email',
                'max:255',
                Rule::unique(Customer::class, 'email'),
            ],
            'phone' => ['required',
                'string',
                'max:15',
                Rule::unique(Customer::class, 'phone')
            ]
        ]);

        if ($validator->fails()) {
            throw new IncompleteCustomerAttributesException($validator);
        }
    }

    /**
     * Register a new customer.
     *
     * This method registers a new customer in the system after validating the provided attributes.
     * It ensures that all necessary fields are provided and that they meet the specified rules.
     * If the registration is successful, it returns a positive API response with the customer data.
     * If an error occurs, an API response with the error message is returned.
     *
     * @param array $attributes Array of customer attributes (document ID, name, email, phone).
     * @return \App\Services\ApiResponse The API response containing the result of the operation.
     */
    public function register($attributes)
    {
        try {
            $this->validateFields($attributes);

            $customer = Customer::create($attributes);

            $walletService = Wallet::create($customer);

            $data = [
                'customer' => $customer->toArray(),
                'wallet' => $walletService->toArray()
            ];

            return \Api::response(true, '00', __('Customer registered successfully'), $data);
        } catch (\Exception $e) {
            return \Api::response(false, $e->status, $e->getMessage(), $e->errors());
        }
    }
}