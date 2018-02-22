<?php
namespace Asanzred\Ingenico;

use Ingenico\Connect\Sdk\Client;

use Ingenico\Connect\Sdk\Domain\Hostedcheckout\CreateHostedCheckoutRequest;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\HostedCheckoutSpecificInput;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\Order;
use Ingenico\Connect\Sdk\Domain\Definitions\FraudFields;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;

class IngenicoHostedCheckoutRequest extends IngenicoRequest {
    
    protected $order;
    protected $fraud;
    protected $hostedCheckoutSpecificInput;
    
    protected $request;

    /**
    * {@inheritDoc}
    */
    public function __construct($params)
    {
        parent::__construct($params);
    }
    

    /**
    * Set Order
    *
    * @param Order $order
    *
    * @return void
    */
    public function setOrder(Order $order)
    {
        /*  Can't do $this->order = $order  Cause it throws a FatalError */
        $orderNew = new Order();
            $orderNew->additionalInput = $order->additionalInput;
            $orderNew->amountOfMoney   = $order->amountOfMoney;
            $orderNew->customer        = $order->customer;
            $orderNew->items           = $order->items;
            $orderNew->references      = $order->references;
            $orderNew->shoppingCart    = $order->shoppingCart;
        $this->order = $orderNew;
    }

    /**
    * Set Fraud. 
    *
    * @param FraudFields $fraudFields
    *
    * @return void
    */
    public function setFraudFields(FraudFields $fraudFields)
    {
        $this->fraudFields = $fraudFields;
    }

    /**
    * Set hostedCheckoutSpecificInput
    *
    * @param HostedCheckoutSpecificInput $hostedCheckoutSpecificInput
    *
    * @return void
    */
    public function setHostedCheckoutSpecificInput(HostedCheckoutSpecificInput $hostedCheckoutSpecificInput)
    {
        $this->hostedCheckoutSpecificInput = $hostedCheckoutSpecificInput;
    }

    /**
    * Send the HostedCheckout request to Ingenico payment
    *
    * @return Response
    */
    public function send()
    {
        //Create the Hosted Checkout request and set the main fields
        $request = new CreateHostedCheckoutRequest();
        $request->order         = $this->order;
        $request->fraudFields   = $this->fraudFields;
        $request->hostedCheckoutSpecificInput = $this->hostedCheckoutSpecificInput;

        $merchantId = $this->merchantId;
        $client     = $this->client;

        $response = $client->merchant($merchantId)->hostedcheckouts()->create($request);
        return $response;
    }

    /**
    * Get the transaction status for the checkout ID passed by parameter
    *
    * @param String $checkoutId
    *
    * @return Response
    */
    public function getCheckoutStatus($checkoutId)
    {
        $merchant   = $this->getMerchant();
        $response   = $merchant->hostedcheckouts()->get($checkoutId);

        return $response;
    }
}