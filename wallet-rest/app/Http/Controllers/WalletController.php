<?php

namespace App\Http\Controllers;

use App\Facades\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recharge(Request $request)
    {
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
     */
    public function payment(Request $request)
    {
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
     */
    public function paymentConfirm(Request $request)
    {
        $result = Wallet::paymentConfirmation(
            session_id: $request->input('session_id'),
            token: $request->input('token')
        );

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balanceInquiry(Request $request)
    {
        $result = Wallet::balanceInquiry(
            documentId: $request->input('document_id'),
            phone: $request->input('phone'),
        );

        return response()->json($result);
    }
}
