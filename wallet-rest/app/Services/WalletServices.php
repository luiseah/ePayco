<?php

namespace App\Services;


use Laminas\Soap\Client;

class WalletServices
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client("http://wallet-soap.local/api/wallet/wsdl");
    }

    /**
     * @param $document
     * @param $name
     * @param $email
     * @param $phone
     * @return mixed
     */
    public function customerRegistration($document, $name, $email, $phone)
    {
        return $this->client->customerRegistration($document, $name, $email, $phone);
    }

}