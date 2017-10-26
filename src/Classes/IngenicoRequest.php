<?php
namespace Bardela\Ingenico;

use Config;
use Ingenico\Connect\Sdk\CommunicatorConfiguration;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\DefaultConnection;
use Ingenico\Connect\Sdk\Client;

use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\HostedCheckoutSpecificInput;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\CreateHostedCheckoutRequest;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\PaymentProductFiltersHostedCheckout;
use Ingenico\Connect\Sdk\Domain\Definitions\PaymentProductFilter;

class IngenicoRequest {
    protected $apiKey;
    protected $secret;
    protected $endPoint;
    protected $client;
    protected $merchantId;
    protected $baseRedirectUrl;

    /**
    * It sets up the Client with the params defined in the config file
    *
    * @return void
    */
    public function __construct()
    {

        $this->apiKey           = Config::get('ingenico.api_key');
        $this->secret           = Config::get('ingenico.secret_key');
        $this->endPoint         = Config::get('ingenico.end_point');
        $this->merchantId       = Config::get('ingenico.merchant');
        $this->baseRedirectUrl  = Config::get('ingenico.base_redirect_url');

        //set the client which needs to set the connection first
        $communicatorConfiguration = new CommunicatorConfiguration($this->apiKey,$this->secret,$this->endPoint,'Ingenico');
        //$communicator = new Communicator(new DefaultConnection(),$communicatorConfiguration);
        $communicatorCustom = new CustomCommunicator(new DefaultConnection(),$communicatorConfiguration);

        //$this->client = new Client($communicator);
        $this->client = new CustomClient($communicatorCustom);
    }

    /**
    * @return Merchant
    */
    public function getMerchant()
    {
        $client     = $this->client;
        $merchantId = $this->merchantId;
        $merchant   = $client->merchant($merchantId);
        return $merchant;
    }

    /**
    * @return Client
    */
    public function getClient()
    {
        return $this->client;
    }

    /**
    * It extracts the relevant info from the Exception
    *
    * @return mixed[] Array of msg and request url
    */
    public function getErrorInfo(Exception $e){
        $msg    = $e->getMessage();
        $reqUrl = $e->getFile().":".$e->getLine();

        if (isset($e->getTrace()[0]['args'][1]->errors[0]->message))
            $msg    = $e->getTrace()[0]['args'][1]->errors[0]->message;
        if (isset($e->getTrace()[1]['args'][1]))
            $reqUrl     = $e->getTrace()[1]['args'][1];
        //$eCategory  = $e->getTrace()[0]['args'][1]->errors[0]->category;
        
        return ['msg'=>$msg, 'request_url'=>$reqUrl];
    }

    /**
    * It test the connection works
    *
    * @return mixed[] Connection result 
    */
    public function testConnection()
    {
        $classname = get_class($this->client);
        $merchant       = $this->client->merchant($this->merchantId);
        $testconnection = $merchant->services()->testconnection();
        return $testconnection;
    }

    /**
    * It sends an approve request to Ingenico for the paymentId passed by parameter
    *
    * @param string $paymentId
    *
    * @return Response Approve response
    */
    public function approvePayment($paymentId)
    {
        /*
        $references = new OrderReferencesApprovePayment();
        $order = new OrderApprovePayment();
        $order->references = $references;
        */
        $body = new ApprovePaymentRequest();
        //$body->order = $order;
        
        $merchant   = $this->getMerchant();
        $response = $merchant->payments()->approve($paymentId, $body);
        
        return $response;
    }
}