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
     * @return  String
     */
    public function testConnection()
    {
        $ingenicoRequest = new IngenicoRequest();
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
     * @param  IngenicoAttributesWrapper    $attributesWrapper
     * @param  String                       $returnUrl
     */
    public function payment(IngenicoAttributesWrapper $attributesWrapper, $returnUrl=null)
    {
        /* ---------------------------------------------
        * Set all the data to do the request
        * -------------------------------------------- */  
        //$this->attributesWrapper = $attributesWrapper;
        //$this->attributesWrapper->returnUrl = $returnUrl;

        $order          = $attributesWrapper->buildOrder();
        $fraud          = $attributesWrapper->buildFraud();
        $hostedCheckout = $attributesWrapper->buildHostedCheckout($returnUrl);

        /* ---------------------------------------------
        * Prepare the Request
        * -------------------------------------------- */
        $request = new IngenicoHostedCheckoutRequest();
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
     * It retrieves the payment status pass by parameter in the server using the Ingenico SDK payment API
     *
     * @param  String   $checkoutId
     *
     * @return Response
     */
    public function getPaymentStatus($checkoutId)
    {
        $ingenicoRequest    = new IngenicoHostedCheckoutRequest();
        $response = $ingenicoRequest->getStatus($checkoutId);

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
     * @param  String   $checkoutId
     *
     * @return Response
     */
    public function getPaymentStatus($checkoutId)
    {
        $ingenicoRequest    = new IngenicoHostedCheckoutRequest();
        $response = $ingenicoRequest->getStatus($checkoutId);

        return $response;
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
        $ingenicoRequest    = new IngenicoRequest();
        $response = $ingenicoRequest->approvePayment($paymentId);
        
        return $response;
    }

}