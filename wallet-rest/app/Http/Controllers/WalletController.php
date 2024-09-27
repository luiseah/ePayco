<?php

namespace App\Http\Controllers;

use App\Facades\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function recharge(Request $request)
    {
        $this->validate($request, [
            'document_id' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
            'amount' => [
                'required',
                'integer',
            ],
        ]);

        $result = Wallet::recharge(
            $request->input('document_id'),
            $request->input('phone'),
            $request->input('amount'),
        );

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function payment(Request $request)
    {
        $this->validate($request, [
            'document_id' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
            'amount' => [
                'required',
                'integer',
            ],
        ]);

        $result = Wallet::payment(
            $request->input('document_id'),
            $request->input('phone'),
            $request->input('amount'),
        );

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function paymentConfirm(Request $request)
    {
        $this->validate($request, [
            'session_id' => [
                'required',
                'string',
                'max:255',
            ],
            'token' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $result = Wallet::paymentConfirmation(
            $request->input('session_id'),
            $request->input('token')
        );

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function balanceInquiry(Request $request)
    {
        $this->validate($request, [
            'document_id' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $result = Wallet::balanceInquiry(
            documentId: $request->input('document_id'),
            phone: $request->input('phone'),
        );

        return response()->json($result);
    }
}
