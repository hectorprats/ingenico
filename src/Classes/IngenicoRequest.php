<?php
namespace Smallworldfs\Ingenico;

use Config;
use Ingenico\Connect\Sdk\CommunicatorConfiguration;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\DefaultConnection;
use Ingenico\Connect\Sdk\Client;

use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\HostedCheckoutSpecificInput;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\CreateHostedCheckoutRequest;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\PaymentProductFiltersHostedCheckout;
use Ingenico\Connect\Sdk\Domain\Definitions\PaymentProductFilter;
use Ingenico\Connect\Sdk\Domain\Payment\ApprovePaymentRequest;

class IngenicoRequest {
    protected $apiKey;
    protected $secret;
    protected $endPoint;
    protected $client;
    protected $merchantId;
    protected $baseRedirectUrl;

    /**
    * It sets up the Client with the params defined in the config file
    *  $params has the following structure:
    *       [
    *            'apikey'           => , //string Ingenico API Key 
    *            'secret'           => , //string Ingenico Secret Key
    *            'endpoint'         => , //string Ingenico EndPoint Api Url
    *            'merchant'         => , //string Ingenico merchant Id
    *            'base_redirect_url'  => , //Base url for the checkout requests
    *            'returnUrl'        => , //Return url after the payment has been made
    *       ]
    *
    * @param string[] $params array of settings with the structure described above
    * @return void
    */
    public function __construct($params)
    {

        $this->apiKey           = $params['apikey'];
        $this->secret           = $params['secret'];
        $this->endPoint         = $params['endpoint'];
        $this->merchantId       = $params['merchant'];
        $this->baseRedirectUrl  = $params['base_redirect_url'];
        $this->returnUrl        = $params['return_url'];
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
    * Set the merchant Id to the one pass by parameter
    * @return int $merchantId
    */
    public function setMerchant($merchantId)
    {
        $this->merchantId = $merchantId;
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

    /**
    * Get the transaction status for the payment ID passed by parameter
    *
    * @param String $paymentId
    *
    * @return Response
    */
    public function getPaymentStatus($paymentId)
    {
        $merchant   = $this->getMerchant();
        $response = $merchant->payments()->get($paymentId);

        return $response;
    }
}