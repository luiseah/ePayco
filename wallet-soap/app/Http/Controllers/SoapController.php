<?php

namespace App\Http\Controllers;

use App\Services\WebService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Laminas\Soap\AutoDiscover as WsdlAutoDiscover;
use Laminas\Soap\Server as SoapServer;

class SoapController extends Controller
{
    public function wsdlAction(Request $request)
    {
        if (!$request->isMethod('get')) {
            return $this->prepareClientErrorResponse('GET');
        }

        $wsdl = new WsdlAutoDiscover();

        $wsdl->setUri(route('soap-server'))
            ->setServiceName('WalletService');

        $this->populateServer($wsdl);

        return response()->make($wsdl->toXml())
            ->header('Content-Type', 'application/xml');
    }

    private function populateServer($server)
    {
        $server->setClass(WebService::class);
    }

    private function prepareClientErrorResponse($allowed)
    {
        return response()->make('Method not allowed', 405)->header('Allow', $allowed);

    }

    public function serverAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->prepareClientErrorResponse('POST');
        }

        $server = new SoapServer(
            route('soap-wsdl'),
            [
                'actor' => route('soap-server'),
            ]
        );

        $server->setReturnResponse(true);
        $this->populateServer($server);
        $soapResponse = $server->handle();

        return response($soapResponse)
            ->header('Content-Type', 'application/xml');
    }
}
