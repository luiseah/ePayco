<?php

namespace App\Services;


use App\Services\Responses\ApiResponse;

class WebService
{
    /**
     * Register a new customer.
     *
     * This method registers a new customer using their document ID, name, email, and phone number.
     *
     * @param string $documentId Customer's document ID.
     * @param string $name Customer's full name.
     * @param string $email Customer's email address.
     * @param string $phone Customer's phone number.
     * @return \App\Services\Responses\ApiResponse The API response containing the registration status and data.
     */
    public function customerRegistration(string $documentId, string $name, string $email, string $phone)
    {
        return \Customer::register([
            'document_id' => $documentId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);
    }

    /**
     * Recharge the customer's wallet.
     *
     * This method processes a wallet recharge for the customer using their document ID and phone number.
     *
     * @param string $documentId Customer's document ID.
     * @param string $phone Customer's phone number.
     * @param int $amount The amount to be recharged into the wallet.
     * @return \App\Services\Responses\ApiResponse The API response indicating the recharge status.
     */
    public function recharge(string $documentId, string $phone, int $amount)
    {
        return \Wallet::recharge([
            'document_id' => $documentId,
            'phone' => $phone,
            'amount' => $amount
        ]);
    }

    /**
     * Process a payment.
     *
     * This method processes a payment by deducting the specified amount from the customer's wallet.
     *
     * @param string $documentId Customer's document ID.
     * @param string $phone Customer's phone number.
     * @param int $amount The amount to be deducted for the payment.
     * @return \App\Services\Responses\ApiResponse The API response indicating the payment status.
     */
    public function payment(string $documentId, string $phone, int $amount): ApiResponse
    {
        return \Wallet::payment([
            'document_id' => $documentId,
            'phone' => $phone,
            'amount' => $amount
        ]);
    }

    /**
     * Confirm a payment.
     *
     * This method confirms a payment by verifying the session ID and token associated with the transaction.
     *
     * @param string $sessionId The session ID of the payment transaction.
     * @param string $token The token used to verify the payment.
     * @return \App\Services\Responses\ApiResponse The API response confirming the payment.
     */
    public function paymentConfirmation(string $sessionId, string $token): ApiResponse
    {
        return \Wallet::paymentConfirmation([
            'session_id' => $sessionId,
            'token' => $token
        ]);
    }

    /**
     * Balance Inquiry.
     *
     * This method allows the customer to check their wallet balance by providing their document ID and contact details.
     *
     * @param string $documentId Customer's document ID.
     * @param string $name Customer's full name.
     * @param string $email Customer's email address.
     * @param string $phone Customer's phone number.
     * @return \App\Services\Responses\ApiResponse The API response containing the customer's balance details.
     */
    public function balanceInquiry(string $documentId, string $name, string $email, string $phone): ApiResponse
    {
        return \Api::response(true, '0', 'Balance inquiry processed successfully', [
            'documentId' => $documentId,
            'name' => $name,
            'email' => $email,
            'mobile' => $phone
        ]);
    }
}
