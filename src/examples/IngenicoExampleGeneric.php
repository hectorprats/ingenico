<?php
namespace Bardela\Ingenico;

use Config;
use Bardela\Ingenico\IngenicoAttributtesWrapper;
abstract class IngenicoExampleGeneric
{
    /** 
    * Url that we want to be loaded after finishing the Hosted checkout payment redirect
    * @var string 
    */
    protected $returnUrl;

    /**
    * Initialize Example. Use the return url defined in the config file or the one passed by parameter
    *
    * @param String $returnUrl 
    */ 
    public function __construct($returnUrl=null)
    {
        $this->returnUrl = isset($returnUrl) ? $returnUrl :  Config::get('ingenico.return_url');
    }

    /**
    * It returns an associative array of inputs form fields that match the IngenicoAttributesWrapper properties
    * The array has this structure:
    * array (
    *   'inputname' => 'value1',
    *   ...
    *   'inputnameN' => 'valueN'
    * );
    *
    * @return string[string] Array of fields
    */
    abstract protected function getDefaultData();


    /**
    * It returns an associative array of inputs form fields that match the IngenicoAttributesWrapper properties
    * The array has this structure
    * array (
    *   'inputgroup1' => 
    *       array(
    *           0   => 
    *              array (
    *                  'type'  => 'text',
    *                   'name'  => 'Fieldname1',
    *                   'value' => 'value1'
    *               )
    *           1   => 
    *               array (
    *                   'type'  => 'select',
    *                   'name'  => 'Fieldname2',
    *                   'options' => array('option1','option2')
    *               )
    *       ...
    *       ),
    *    ...
    *   'inputgroupN' => ...
    *   );
    *
    * @return string[string][int][string] Array of input fields
    */
    abstract protected function getInputFields();

    /**
    * It sets the properties IngenicoAttributesWrapper for the the Hosted Checkout Request
    * 
    * @param string[string] $inputFields array of input names and its values
    *
    * @return IngenicoAttributesWrapper
    */
    public function setData($inputFields=null)
    {
        $ingenicoAttributesWrapper = new IngenicoAttributesWrapper();

        if ($inputFields == null)
        {
            $inputFields = $this->getDefaultData();
        }
        if (!is_array($inputFields) || count($inputFields)==0)
        {
            return null;
        }
        foreach ($inputFields as $key => $value) 
        {
                if ( !property_exists($ingenicoAttributesWrapper, $key))
                {
                    error_log(__FILE__.":".__LINE__.": The key $key was not found as a valid IngenicoAttributesWrapper property");
                    continue;
                }
                //else
                $ingenicoAttributesWrapper->{$key} = $value;
        }
        $ingenicoAttributesWrapper->returnUrl = $this->returnUrl;
        return $ingenicoAttributesWrapper;
    }

    /**
    * It builds a Checkout Hosted Request using the IngenicoAttributesWrapper and send the request
    * The response Object must be something like this:
    * CreateHostedCheckoutResponse {
    *   +RETURNMAC: "6d4..."
    *   +hostedCheckoutId: "ee5..."
    *   +invalidTokens: null
    *   +partialRedirectUrl: "pay1.preprod.secured-by-ingenico.com:443/checkout/...?requestToken=c.."
    * }
    * 
    * @param string[string] Array of input name-value's pairs that match with IngenicoAttributesWrapper properties
    *
    * @return CreateHostedCheckoutResponse that can be printed
    */
    public function run($inputFields=null)
    {

        /* ---------------------------------------------
        * Set all the data to do the request
        * -------------------------------------------- */        
        $requestAttributes = $this->setData($inputFields);
        $order          = $requestAttributes->buildOrder();
        $fraud          = $requestAttributes->buildFraud();
        $hostedCheckout = $requestAttributes->buildHostedCheckout();

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

}