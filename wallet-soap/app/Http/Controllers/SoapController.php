<?php

namespace App\Http\Controllers;

use App\Services\WebService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laminas\Soap\AutoDiscover as WsdlAutoDiscover;
use Laminas\Soap\Server as SoapServer;

class SoapController extends Controller
{
    /**
     * @param Request $request
     * @return Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
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

    /**
     * @param $server
     */
    private function populateServer($server)
    {
        $server->setClass(WebService::class);
    }

    /**
     * @param $allowed
     * @return Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function prepareClientErrorResponse($allowed)
    {
        return response()->make('Method not allowed', 405)->header('Allow', $allowed);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response|\Laravel\Lumen\Http\ResponseFactory|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function serverAction(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->prepareClientErrorResponse('POST');
        }

        $server = new SoapServer(route('soap-wsdl'), [
            'actor' => route('soap-server')
        ]);

        $server->setReturnResponse(true);
        $this->populateServer($server);
        $soapResponse = $server->handle();

        return response($soapResponse)
            ->header('Content-Type', 'application/xml');
    }
}
