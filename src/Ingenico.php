<?php namespace Bardela\Ingenico;

use Closure;

/**
 *
 * Laravel wrapper for Ingenico Hosted Checkout payment
 *
 */
class Ingenico {

    /**
     * It tests the connection to the Ingenico SDK with the values setted in the config file
     *
     * @param   string[] $params array of settings with the structure
     * @return  string
     */
    public function testConnection($params)
    {
        $ingenicoRequest = new IngenicoRequest($params);
        return $ingenicoRequest->testConnection();
    }

    /**
     * Returns the wrapper needed it to set up in order to do the payment
     *
     * @return  IngenicoAttributesWrapper
     */
    public function getAttributtesWrapper()
    {
        $attributesWrapper = new IngenicoAttributesWrapper();
        return $attributesWrapper;
    }

    /**
     * Sets the payment request and sends it
     *
     * @param  string[] $params 
     * @param  IngenicoAttributesWrapper    $attributesWrapper
     //* @param  String                       $returnUrl
     * @param  string[] $params array of settings with the structure
     */
    //public function payment(IngenicoAttributesWrapper $attributesWrapper, $returnUrl=null)
    public function payment($params, IngenicoAttributesWrapper $attributesWrapper)
    {
        /* ---------------------------------------------
        * Set all the data to do the request
        * -------------------------------------------- */  
        //$this->attributesWrapper = $attributesWrapper;
        //$this->attributesWrapper->returnUrl = $returnUrl;

        $order          = $attributesWrapper->buildOrder();
        $fraud          = $attributesWrapper->buildFraud();
        $returnUrl      = $params['return_url'];
        $hostedCheckout = $attributesWrapper->buildHostedCheckout($returnUrl);

        /* ---------------------------------------------
        * Prepare the Request
        * -------------------------------------------- */
        $request = new IngenicoHostedCheckoutRequest($params);
        $request->setOrder($order);
        $request->setFraudFields($fraud);
        $request->setHostedCheckoutSpecificInput($hostedCheckout);

        /* ---------------------------------------------
        * Send the request
        * -------------------------------------------- */
        $response   = $request->send();

        return $response;
    }

    /**
     * It retrieves the merchant
     *
     * @return Merchant
     */
    public function getMerchant()
    {
        $ingenicoRequest    = new IngenicoHostedCheckoutRequest();
        $merchant = $ingenicoRequest->getMerchant();

        return $merchant;
    }

    /**
     * It retrieves the payment status pass by parameter in the server using the Ingenico SDK payment API
     *
     * @param  string[] $params array of settings with the structure
     * @param  String   $checkoutId
     *
     * @return Response
     */
    public function getCheckoutStatus($params, $checkoutId)
    {
        $ingenicoRequest    = new IngenicoHostedCheckoutRequest($params);
        $response = $ingenicoRequest->getCheckoutStatus($checkoutId);

        return $response;
    }

    /**
     * It retrieves the payment status pass by parameter in the server using the Ingenico SDK payment API
     *
     * @param  string[] $params array of settings with the structure
     * @param  String   $paymentId
     *
     * @return Response
     */
    public function getPaymentStatus($params, $paymentId)
    {
        $ingenicoRequest    = new IngenicoRequest($params);
        $response = $ingenicoRequest->getPaymentStatus($paymentId);
        return $response;
    }

    /**
    * It sends an approve request to Ingenico for the paymentId passed by parameter
    *
    * @param string[] $params array of settings with the structure
    * @param string $paymentId
    *
    * @return Response Approve response
    */
    public function approvePayment($params, $paymentId)
    {
        $ingenicoRequest    = new IngenicoRequest($params);
        $response = $ingenicoRequest->approvePayment($paymentId);
        
        return $response;
    }

}