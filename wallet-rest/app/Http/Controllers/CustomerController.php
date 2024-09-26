<?php

namespace App\Http\Controllers;

use App\Facades\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
