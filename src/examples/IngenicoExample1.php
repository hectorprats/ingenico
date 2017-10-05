<?php
namespace Bardela\Ingenico;

use Config;
use Bardela\Ingenico\IngenicoAttributtesWrapper;
class IngenicoExample1 extends IngenicoExampleGeneric
{
    //Hosted Checkout Specicig Input
    public $returnUrl;
    public $locale     = "en_GB";
    //$isRecurring  = false;
    //$showResultPage   = true;
    //tokens            = "";
    //$variant      = "";
        //Payment Product Filters
        //$excludedProductsId   = [114];
        //$restrictedProductsId = [1,2,3];
        //tokensOnly            = false;

    //Order
        //AdditionalOrderInput: skip
        //orderDate = "20170922135901"; //YYYYMMDDHH24MISS
        //Billing Address
        public $country    = "US"; //ISO 2
        public $state      = "California";
        public $city       = "Los Angeles";
        public $zip        = "0001";
        public $street     = "Rodeo Road";
        public $houseNumber    = "1";
        public $additionalInfo = "b";
        //Company Information
        public $companyName = "SW";
        //Contact Details
        public $emailAddress       = "johndoe@test.com";
        public $emailMessageType   = "plain-text";
        public $faxNumber          = "0199999998";
        public $phoneNumber        = "0199999999";
        //Fiscal Number
        public $fiscalNumber   = "7777666";
        //Locale
        //$locale     = "en_GB"; //defined 2nd line
        //Merchant Customer Id
        public $merchantCustomerId = "0000123";
        //Personal Info
        public $dateOfBirth    = "19601230";
        public $gender         = "male";
            //PersonalName
            public $firstName      = "John";
            public $surname        = "Doe"; //Required
            public $surnamePrefix  = "";
            public $title          = "Mr.";
        //Address Personal for Shipping
        public $shipping_country    = "US"; //ISO 2
        public $shipping_state      = "California";
        public $shipping_city       = "Los Angeles";
        public $shipping_zip        = "0001";
        public $shipping_street     = "Rodeo Road";
        public $shipping_houseNumber    = "1";
        public $shipping_additionalInfo = "b";
        //vatNumber
        public $vatNumber    = "3366644477";

        //AmountOfMoney
        public $amount     = 9900; //99
        public $currency   = "USD";
        //Items
        //https://epayments.developer-ingenico.com/documentation/api/server/#schema_LineItem

        //References
        //$merchantOrderId    =  //order identifier. Note: This does not need to have a unique value for each transaction
        public $merchantReference  = "123456";
        public $descriptor         = "";
        //$invoiceData        = //skip

        //Shopping Cart https://epayments.developer-ingenico.com/documentation/api/server/#schema_AmountBreakdown
        //$amountBreakdown    = [['amount' => $amount, 'type' =>'VAT']]

    //Fraud Fields: skip
    /*
    $addressesAreIdentical  = true;
    $blackListData          = "";
    //$cardOwnerAddress;
    $customerIpAddress      = "192.168.1.1";
    $defaultFormFill        = "manually ";   
    $fingerPrintActivated   = false;
    //$giftCardType           = "birthday";
    //$giftMessage            = "";
    $hasForgottenPwd        = false;
    $hasPassword            = true;
    $isPreviousCustomer     = true;
    $orderTimezone          = "US"; //ISO 3166 2-character country code
    $shipComments           = "";
    $shipmentTrackingNumber = "";
    //$shippingDetails;
    $userData               = [];
    $website                = "";
    */
    /*
    public function __construct($returnUrl=null)
    {
        parent::__construct();
    }
    */

    /**
    * {@inheritDoc}
    * It sets the properties IngenicoAttributesWrapper for the the Hosted Checkout Request
    * It is just an example, set the IngenicoAttributesWrapper you need for the request 
    * 
    * @param String $returnUrl
    *
    * @return IngenicoAttributesWrapper
    */
    protected function setData()
    {
        /* ---------------------------------------------
        * Set the values for the Hosted Checkout Request
        * -------------------------------------------- */
        $requestAttributes = new IngenicoAttributesWrapper();
        $requestAttributes->returnUrl   = $this->returnUrl;
        $requestAttributes->locale      = $this->locale;

        //Order
        //AdditionalOrderInput: skip
        //orderDate = "20170922135901"; //YYYYMMDDHH24MISS
        //Billing Address
        $requestAttributes->bi_countryCode  = $this->country;
        $requestAttributes->bi_state        = $this->state;
        $requestAttributes->bi_city         = $this->city;
        $requestAttributes->bi_zip          = $this->zip;
        $requestAttributes->bi_street       = $this->street;
        $requestAttributes->bi_houseNumber  = $this->houseNumber;
        $requestAttributes->bi_additionalInfo = $this->additionalInfo;
        //Company Information
        $requestAttributes->ci_name = $this->companyName;
        //Contact Details
        $requestAttributes->cd_emailAddress     = $this->emailAddress;
        $requestAttributes->cd_emailMessageType = $this->emailMessageType;
        $requestAttributes->cd_faxNumber        = $this->faxNumber;
        $requestAttributes->cd_phoneNumber      = $this->phoneNumber;
        //Fiscal Number
        $requestAttributes->fiscalNumber = $this->fiscalNumber;

        $requestAttributes->merchantCustomerId = $this->merchantCustomerId;
        //Personal Info
        $requestAttributes->dateOfBirth = $this->dateOfBirth;
        $requestAttributes->gender      = $this->gender;
            //PersonalName
            $requestAttributes->pi_firstName    = $this->firstName;
            $requestAttributes->pi_surname      = $this->surname;
            $requestAttributes->pi_surnamePrefix = $this->surnamePrefix;
            $requestAttributes->pi_title        = $this->title;

        //Shipping details
        $requestAttributes->sa_countryCode  = $this->shipping_country;
        $requestAttributes->sa_state        = $this->shipping_state;
        $requestAttributes->sa_city         = $this->shipping_city;
        $requestAttributes->sa_zip          = $this->shipping_zip;
        $requestAttributes->sa_street       = $this->shipping_street;
        $requestAttributes->sa_houseNumber  = $this->shipping_houseNumber;
        $requestAttributes->sa_additionalInfo = $this->shipping_additionalInfo;

        $requestAttributes->vatNumber = $this->vatNumber;

        //amount
        $requestAttributes->amount      = $this->amount;
        $requestAttributes->currency    = $this->currency;
        //references
        $requestAttributes->merchantReference   = $this->merchantReference;
        $requestAttributes->descriptor          = $this->descriptor;

        //Return the object
        return $requestAttributes;
    }

}