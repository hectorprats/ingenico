<?php
namespace Bardela\Ingenico;

use Config;
use Bardela\Ingenico\IngenicoAttributtesWrapper;
abstract class IngenicoExampleGeneric
{
    //Hosted Checkout Specicig Input
    public $returnUrl;

    /**
    * Initialize Example
    */ 
    public function __construct($returnUrl=null)
    {
        $this->returnUrl = isset($returnUrl) ? $returnUrl :  Config::get('ingenico.return_url');
    }

    /**
    * It sets the properties IngenicoAttributesWrapper for the the Hosted Checkout Request
    * Override this function and set all the data you want
    * 
    * @param String $returnUrl
    *
    * @return IngenicoAttributesWrapper
    */
    abstract protected function setData();

    /**
    * It builds a Checkout Hosted Request using the IngenicoAttributesWrapper and send the request
    * The response must be something like this:
    * CreateHostedCheckoutResponse {#503 ▼
    *   +RETURNMAC: "6d4..."
    *   +hostedCheckoutId: "ee5..."
    *   +invalidTokens: null
    *   +partialRedirectUrl: "pay1.preprod.secured-by-ingenico.com:443/checkout/...?requestToken=c.."
    * }
    * 
    * @return CreateHostedCheckoutResponse that can be printed
    */
    public function run()
    {

        /* ---------------------------------------------
        * Set all the data to do the request
        * -------------------------------------------- */        
        $requestAttributes = $this->setData();

        $order          = $requestAttributes->buildOrder();
        $fraud          = $requestAttributes->buildFraud();
        $hostedCheckout = $requestAttributes->buildHostedCheckout();

        /* ---------------------------------------------
        * Send the Request
        * -------------------------------------------- */
        $request = new IngenicoHostedCheckoutRequest();
        $request->setOrder($order);
        $request->setFraudFields($fraud);
        $request->setHostedCheckoutSpecificInput($hostedCheckout);
        
        /* ---------------------------------------------
        * Process the request
        * -------------------------------------------- */
        $response   = $request->send();

        return $response;
    }

}