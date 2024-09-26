<?php

namespace App\Services;

use App\Exceptions\IncompleteTransactionAttributesException;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * CustomerService
 *
 * This class handles customer-related operations, such as validation and registration.
 * It provides a common structure for managing customer data and returning responses
 * for SOAP services.
 */
class TransactionService
{
    /**
     * @param $attributes
     * @return mixed
     * @throws IncompleteTransactionAttributesException
     */
    public function find($attributes)
    {
        $validator = Validator::make($attributes, [
            'session_id' => ['required',
                'string',
                'max:40',
                Rule::exists(Transaction::class, 'session_id')
            ],
            'token' => ['required',
                'string',
                'max:6',
                Rule::exists(Transaction::class, 'token')
            ],
        ]);

        if ($validator->fails()) {
            throw new IncompleteTransactionAttributesException($validator);
        }

        return Transaction::where([
            'session_id' => $attributes['session_id'],
            'token' => $attributes['token']
        ])->firstOrFail();
    }
}