<?php

namespace App\Http\Controllers;

use App\Facades\Wallet;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use app\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $result =  Wallet::customerRegistration(
            $request->input('document_id'),
            $request->input('name'),
            $request->input('email'),
            $request->input('phone')
        );

       return response()->json($result);
    }
}
