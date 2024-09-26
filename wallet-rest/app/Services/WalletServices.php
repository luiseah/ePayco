<?php

namespace App\Services;


use Laminas\Soap\Client;

class WalletServices
{
    /**
     * @var Client
     */
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client("http://wallet-soap.local/api/wallet/wsdl");
    }

    /**
     * Register a new customer.
     *
     * This method registers a new customer using their document ID, name, email, and phone number.
     *
     * @param string $documentId Customer's document ID.
     * @param string $name Customer's full name.
     * @param string $email Customer's email address.
     * @param string $phone Customer's phone number.
     * @return mixed
     */
    public function customerRegistration(string $documentId, string $name, string $email, string $phone)
    {
       return  $this->client->call(__FUNCTION__, func_get_args());
    }

    /**
     * Recharge the customer's wallet.
     *
     * This method processes a wallet recharge for the customer using their document ID and phone number.
     *
     * @param string $documentId Customer's document ID.
     * @param string $phone Customer's phone number.
     * @param int $amount The amount to be recharged into the wallet.
     * @return mixed
     */
    public function recharge(string $documentId, string $phone, int $amount)
    {
        return  $this->client->call(__FUNCTION__, func_get_args());
    }

    /**
     * Process a payment.
     *
     * This method processes a payment by deducting the specified amount from the customer's wallet.
     *
     * @param string $documentId Customer's document ID.
     * @param string $phone Customer's phone number.
     * @param int $amount The amount to be deducted for the payment.
     * @return mixed
     */
    public function payment(string $documentId, string $phone, int $amount)
    {
        return  $this->client->call(__FUNCTION__, func_get_args());
    }

    /**
     * Confirm a payment.
     *
     * This method confirms a payment by verifying the session ID and token associated with the transaction.
     *
     * @param string $sessionId The session ID of the payment transaction.
     * @param string $token The token used to verify the payment.
     * @return mixed
     */
    public function paymentConfirmation(string $sessionId, string $token)
    {
        return  $this->client->call(__FUNCTION__, func_get_args());
    }

    /**
     * Balance Inquiry.
     *
     * This method allows the customer to check their wallet balance by providing their document ID and contact details.
     *
     * @param string $documentId Customer's document ID.
     * @param string $phone Customer's phone number.
     * @return mixed
     */
    public function balanceInquiry(string $documentId, string $phone)
    {
        return  $this->client->call(__FUNCTION__, func_get_args());
    }
}