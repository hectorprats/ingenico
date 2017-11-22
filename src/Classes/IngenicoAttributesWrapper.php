<?php
namespace Bardela\Ingenico;

use Ingenico\Connect\Sdk\Domain\Definitions\AirlineData;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\OrderTypeInformation;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\AdditionalOrderInput;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\Order;

use Ingenico\Connect\Sdk\Domain\Definitions\AmountOfMoney;
use Ingenico\Connect\Sdk\Domain\Definitions\Address;
use Ingenico\Connect\Sdk\Domain\Definitions\CompanyInformation;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\Customer;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\ContactDetails;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\PersonalInformation;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\PersonalName;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\AddressPersonal;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\OrderReferences;
use Ingenico\Connect\Sdk\Domain\Payment\Definitions\OrderInvoiceData;

use Ingenico\Connect\Sdk\Domain\Definitions\FraudFields;
use Ingenico\Connect\Sdk\Domain\Definitions\FraudFieldsShippingDetails;

use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\HostedCheckoutSpecificInput;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\Definitions\PaymentProductFiltersHostedCheckout;
use Ingenico\Connect\Sdk\Domain\Definitions\PaymentProductFilter;


class IngenicoAttributesWrapper
{
    protected $request;

    /* 
    ::::::::::::::::::::::::::
        BLOCKS
        1) hostedCheckoutSpecicigInput
        2) order
        3) fraudFields
    :::::::::::::::::::::::::::::
    */





    /* ====================================
    *   BLOCK 1: hostedCheckoutSpecicigInput
    *       isRecurring
    *       locale
    *       paymentProductFilters
    *       returnUrl
    *       showResultPage
    *       variant
    * =================================== */
    public $isRecurring;    //boolean. Show only payment products that support recurring(true) / one-off (false). Default false
    public $locale;         // string(5). Examples: en_US or en_GB
        /*--paymentProductFilters--*/
        public $excludeGroups;      //array: List containing all payment product groups that should either be excluded from the payment context
        public $excludeProducts;    //array: List containing all payment product ids that should either be excluded to in  the payment context
        public $restrictToGroups;   //array: List containing all payment product groups that should either be restricted from the 
        public $restrictToProducts; //array: List containing all payment product ids that should either be restricted from the 
        public $tokensOnly;         /*  boolean
                                    true - The consumer may only complete the payment using one of the provided accounts on file.
                                    false -The consumer can complete the payment using any way they like, as long as it is allowed in the payment context. Default.
                                    */

    public $returnUrl;      //string (512) The URL that the consumer is redirect to after the payment flow has finished
    public $showResultPage; //boolean: show result page to the consumer if true(default) or redirect if false.
    public $tokens;         //string: String containing comma separated tokens associated with the consumer of this hosted checkout
    public $variant;        //string:  By specifying a specific variant page you can force the use of another variant than the default defined in MyCheckout payment pages
    

    /* ====================================
    *   BLOCK 2: order
    *       additionalInput
    *       amountOfMoney
    *       customer
    *       items
    *       references
    *       shoppingCart
    * =================================- */
        /* -- additionalInput --*/
        /*  Contains:
                    airlineData
                    level3SummaryData
                    numberOfInstallments
                    orderDate
                    typeInformation
        */
        //airlineData
            public $agentNumericCode;   //string (8) Numeric code identifying the agent
            public $code;           //string(3) Airline numeric code Required
            public $flightDate;     //string(8) Date of the Flight. Format: YYYYMMDD
            public $flightLegs = array();     //array AirlineFlightLeg[]  Object that holds the data on the individual legs of the ticket
            /*
            $flightLegs is an Array of associative array with the following keys
                [
                'airlineClass'      => , //string (2)   Reservation Booking Designator Required
                'arrivalAirport'    => , //string (3)   Arrival airport/city code Required
                'carrierCode'       => , //string (2) IATA carrier code Required
                'date'          => , //string (8)   Date of the leg. Format: YYYYMMDD
                'departureTime' => , //string (6)   The departure time in the local time at the departure airport. Format: HH:MM
                'fare'          => , //string (12)  Fare of this leg
                'fareBasis'     => , //string (15)  Fare Basis/Ticket Designator
                'flightNumber'  => , //string (15)  Fare Basis/Ticket Designator
                'number'        => , //string (4)   integer (5) Sequence number of the flight leg Required
                'originAirport' => , //string (3)   Origin airport/city code Required
                'stopoverCode'  => , //string   Values: permitted, non-permitted
                ]
            */
            public $invoiceNumber;  //string(6)    Airline tracing number
            public $isETicket;      //boolean   true = The ticket is an E-Ticket, false = the ticket is not an E-Ticket
            public $isRegisteredCustomer;   //boolean   true = a registered known consumer, false = unknown consumer
            public $isRestrictedTicket;     //boolean true - Restricted, the ticket is non-refundable, false - No restrictions, the ticket is (partially) refundable
            public $isThirdParty;   //boolean   true - The payer is the ticket holder, false - The payer is not the ticket holder
            public $issueDate;      //string (8)    This is the date of issue recorded in the airline system. Format: YYYYMMDD
            public $ad_merchantCustomerId; //string (16)   Your ID of the consumer in the context of the airline data
            public $ad_name;       //string (20)   Name of the airline Required
            public $passengerName;  //string (49)   Name of passenger
            public $placeOfIssue;   //string (15)   Place of issue. For sales in the US the last two characters (pos 14-15) must be the US state code.
            public $pnr;            //string (127)  Passenger name record
            public $pointOfSale;    //string (25)   IATA point of sale name
            public $posCityCode;    //string (10)   city code of the point of sale
            public $ticketDeliveryMethod;    //String values: e-ticket, city-ticket-office, airport-ticket-office, ticket-by-mail, ticket-on-departure
            public $ticketNumber;    //string (13)  The ticket or document number

        //level3SummaryData: Deprecated. Please use Order.shoppingCart instead.
        public $numberOfInstallments;   //integer(5) The number of installments
        public $orderDate; //YYYYMMDDHH24MISS

        //typeInformation
            public $purchaseType; // string values: good, service
            public $usageType;    // string values: private, commercial

        /* -- amountOfMoney --*/
        public $amount;     //integer (12)  Amount in cents and always having 2 decimals Required
        public $currency;   //string (3)  Three-letter ISO currency code representing the currency for the amount Required


        /* ---------------------------------
        *   Customer
        *       billingAddress
        *       companyInformation
        *       contactDetails
        *       fiscalNumber
        *       locale
        *       merchantCustomerId
        *       personalInformation
        *       shippingAddress
        *       vatNumber
        * ---------------------------------- */

        /*  billingAddress      */
            public $bi_additionalInfo; //string (50)   Additional address information
            public $bi_city;           //string (40)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
            public $bi_countryCode;    //string (2)    ISO 3166-1 alpha-2 country code. Required, except when a token include its value
            public $bi_houseNumber;    //string (15)   House number
            public $bi_state;          //string (35)
            public $bi_stateCode;      //string (9)
            public $bi_street;         //string (50)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
            public $bi_zip;            //string(10)    Required for Direct Debit UK (705), except when a token include its value
            
            
            
        /*  companyInformation  */
            public $ci_name;        //string (40)   Name of company, as a consumer
        /*  contactDetails      */
            public $cd_emailAddress;    //string (70)   Email address of the consumer
            public $cd_emailMessageType;    //string (10) plain-text or html
            public $cd_faxNumber;       //string (20)   Fax number of the consumer
            public $cd_phoneNumber;     //string (20)   Phone number of the consumer
        /* fiscalNumber         */
        public $fiscalNumber;
        /* locale               */
        public $c_locale; //String(5) The locale that the consumer should be addressed in (for 3rd parties)
        /* merchantCustomerId   */
        public $merchantCustomerId; //string(15) Your identifier for the consumer. Required for payments with PaysafeCard
        /* personalInformation  */
            public $dateOfBirth;
            public $gender;
            //name
                public $pi_firstName;   //string (15)
                public $pi_surname;     //string (70)   Required for the creation of a Payout
                public $pi_surnamePrefix;   //string (15)   Middle name
                public $pi_title;       //string (35)   Title of consumer
        /* shippingAddress      */
            public $sa_additionalInfo; //string (50)   Additional address information
            public $sa_city;           //string (40)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
            public $sa_countryCode;    //string (2)    ISO 3166-1 alpha-2 country code. Required, except when a token include its value
            public $sa_houseNumber;    //string (15)   House number
            //name
                public $sa_firstName;   //string (15)
                public $sa_surname;     //string (70)   Required for the creation of a Payout
                public $sa_surnamePrefix;   //string (15)   Middle name
                public $sa_title;       //string (35)   Title of consumer
            public $sa_state;          //string (35)
            public $sa_stateCode;      //string (9)
            public $sa_street;         //string (50)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
            public $sa_zip;            //string(10)    Required for Direct Debit UK (705), except when a token include its value
        /* vatNumber        */
        public $vatNumber;

        /* -----------------------------------
        *   Items
        * ---------------------------------- */
        public $items = array(); //array LineItem[]   Shopping cart data
            /*
            Array of associative array with the following keys
                    [
                    'a_amount'          => , //integer (12) Amount in cents and always having 2 decimals
                    'a_currencyCode'    => , //string (3)   Three-letter ISO currency code representing the currency 
                    'i_description'     => , //string (116) Shopping cart item description
                    'i_merchantLinenumber'  => , //string (5) Line number for printed invoice or order of items in the cart
                    'i_merchantPagenumber'  => , //string (3) Page number for printed invoice
                    'i_nrOfItems'       => , //string (4)   Quantity of the item
                    'i_pricePerItem'    => , //integer (12) Price per item
                    //'level3InterchangeInformation' =>  ,//deprecated
                    'o_discountAmount'  => , //integer (12) Discount on the line item, last two digits implied as decimal places
                    'o_lineAmountTotal' => , //integer (12) Total amount for the line item
                    'o_productCode'     => , //string (12)  Product or UPC Code
                    'o_productPrice'    => , //integer (12) The price of one unit of the product
                    'o_productType'     => , //string (12)  Code used to classify items that are purchased
                    'o_quantity'        => , //integer (4)  Quantity of the units being purchased
                    'o_taxAmount'       => , //integer (12) Tax on the line item, last two digits implied as decimal places
                    'o_unit'            => , //string (12)  Indicates the line item unit of measure
                    ]
            */
        /*  references       */
            public $descriptor;     //string(256) advise you to use 22 characters as the max length
            //invoiceData
                public $i_additionalData;   //string (500)  Additional data for printed invoices
                public $i_invoiceDate;      //string (14)   Date and time on invoice. Format: YYYYMMDDHH24MISS
                public $i_invoiceNumber;    //string (20)   Your invoice number
                public $i_textQualifiers;   //Array string(10)[]    Array of 3 text qualifiers
            public $merchantOrderId;    //integer (10)  order identifier. Note: This does not need to have a unique value for 
            public $merchantReference;  //string (30)   Your unique reference of the transaction
        /*  shoppingCart    */ 
        public $amountBreakdown = array();    //array AmountBreakdown[] 
            /*
            Array of associative array with the following keys
                    [
                    'amount'  => , //integer (12) Amount in cents and always having 2 decimals
                    'type'    => , //string   Values: AIRPORT_TAX, CONSUMPTION_TAX, DISCOUNT, DUTY, SHIPPING, VAT
                    ]
            */

    /* ====================================
    *   BLOCK 3: fraudFields
    *       addressesAreIdentical
    *       blackListData
    *       cardOwnerAddress
    *       customerIpAddress
    *       defaultFormFill
    *       fingerPrintActivated
    *       giftCardType
    *       giftMessage
    *       hasForgottenPwd
    *       hasPassword
    *       isPreviousCustomer
    *       orderTimezone
    *       shipComments
    *       shipmentTrackingNumber
    *       shippingDetails
    *       userData
    *       website
    * =================================== */
    
    public $addressesAreIdentical;  //boolean (5)   If invoice and shipping addresses are equal.
    public $blackListData;          //string (50)   Additional black list input
    //cardOwnerAddress
        public $co_additionalInfo; //string (50)   Additional address information
        public $co_city;           //string (40)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
        public $co_countryCode;    //string (2)    ISO 3166-1 alpha-2 country code. Required, except when a token include its value
        public $co_houseNumber;    //string (15)   House number
        public $co_state;          //string (35)
        public $co_stateCode;      //string (9)
        public $co_street;         //string (50)   Required for Invoice payments, Direct Debit UK and the creation of a Payout.
        public $co_zip;            //string(10)    Required for Direct Debit UK (705), except when a token include its value
    public $customerIpAddress;  //string (32)   The IP Address of the consumer that is making the payment
    public $defaultFormFill;    //string (50)   Values: automatically, automatically-but-modified, manually
    public $fingerPrintActivated;   //boolean (5)  device fingerprint has been used while processing the order
    public $giftCardType;   //string (50)   Values: celebrate-fall, grandparents-day, independence-day...
    public $giftMessage;    //string (160)
    public $hasForgottenPwd;    //boolean (5)   if the consumer (initially) had forgotten their password
    public $hasPassword;        //boolean (5)   if the consumer entered a password to access to his/her account
    public $isPreviousCustomer; //boolean (5)   if the consumer has a history of online shopping with the merchant
    public $orderTimezone;      //string (2)    ISO 3166 2-character country code
    public $shipComments;       //string (160)  Comments included during shipping
    public $shipmentTrackingNumber; //string (19)   Shipment tracking number
    //shippingDetails;    //
        public $methodDetails;  //string (50)   Details regarding the shipping method
        public $methodSpeed;     //integer (10)  Shipping method speed indicator
        public $methodType;      //integer (5)   Shipping method type indicator
    public $userData;           //array     Array of up to 16 userData fields, each with a max length of 256 characters
    public $website;            //string (60)   The website from which the purchase was made
    

    /**
    * Build a Customer with all its fields:
    *   billingAddress
    *   companyInformation
    *   fiscalNumber
    *   locale
    *   merchantCustomerId
    *   personalInformation
    *   shippingAddress
    *   vatNumber
    *
    * @return Customer
    */
    private function buildCustomer()
    {
        /*-------------------------------------------------
                 Set up the Customer
        ---------------------------------------------------*/
        //Billing Address
        $billingAddress = new Address();
            $billingAddress->street         = $this->bi_street;
            $billingAddress->houseNumber    = $this->bi_houseNumber;
            $billingAddress->additionalInfo = $this->bi_additionalInfo;
            $billingAddress->zip            = $this->bi_zip;
            $billingAddress->city           = $this->bi_city;
            $billingAddress->state          = $this->bi_state;
            $billingAddress->stateCode      = $this->bi_stateCode;
            $billingAddress->countryCode    = $this->bi_countryCode;

        //Company Information
        $companyInformation = new CompanyInformation();
        if (isset($this->ci_name) )
        {
            $companyInformation->name = $this->ci_name;
        }

        //Contact Details
        $contactDetails = new ContactDetails();
            $contactDetails->emailAddress       = $this->cd_emailAddress;
            $contactDetails->emailMessageType   = $this->cd_emailMessageType;
            $contactDetails->faxNumber          = $this->cd_faxNumber;
            $contactDetails->phoneNumber        = $this->cd_phoneNumber;

        //fiscalNumber
        //locale
        //merchantCustomerId

        //Personal Information
        $personalInformation = new PersonalInformation();
                $personalName = new PersonalName();
                $personalName->firstName    = $this->pi_firstName;
                $personalName->surname      = $this->pi_surname;
                $personalName->surnamePrefix= $this->pi_surnamePrefix;
                $personalName->title        = $this->pi_title;
            $personalInformation->name          = $personalName;
            $personalInformation->dateOfBirth   = $this->dateOfBirth; 
            $personalInformation->gender        = $this->gender;

        //shippingAddress
        $shippingAddress = new AddressPersonal();
            $shippingAddress->additionalInfo = $this->sa_additionalInfo;
            $shippingAddress->city       = $this->sa_city;
            $shippingAddress->countryCode= $this->sa_countryCode;
            $shippingAddress->houseNumber= $this->sa_houseNumber;
            $shippingAddress->state      = $this->sa_state;
            $shippingAddress->stateCode  = $this->sa_stateCode;
            $shippingAddress->zip        = $this->sa_zip;
            $shippingAddress->street     = $this->sa_street;
            //name
                $shippingPersonalName = new PersonalName();
                $shippingPersonalName->firstName    = $this->sa_firstName;
                $shippingPersonalName->surname      = $this->sa_surname;
                $shippingPersonalName->surnamePrefix= $this->sa_surnamePrefix;
                $shippingPersonalName->title        = $this->sa_title;
            $shippingAddress->name = $shippingPersonalName;
            
        //vatNumber


        $customer = new Customer();
        $customer->billingAddress       = $billingAddress;
        
        $customer->companyInformation   = $companyInformation;
        $customer->contactDetails       = $contactDetails;
        $customer->fiscalNumber         = $this->fiscalNumber;
        $customer->locale               = $this->locale;
        $customer->merchantCustomerId   = $this->merchantCustomerId;
        $customer->personalInformation  = $personalInformation;
        $customer->shippingAddress      = $shippingAddress;
        $customer->vatNumber            = $this->vatNumber;
        
        return $customer;
    }

    /**
    * Build an Order with all its fields:
    *   additionalInput
    *   amountOfMoney
    *   customer
    *   items
    *   references
    *   shoppingCart
    *
    * @return Order
    */
    public function buildOrder()
    {
        /* -- additionalInput --*/
        /*-------------------------------------------------*/
        //        Set up the Additional Order Input
        /*-------------------------------------------------*/
        //airlineData
        $airlineData = null;
        if ( isset($this->code) && isset($this->ad_name) )
        {    
            $airlineData = new AirlineData();
            $airlineData->agentNumericCode;
            $airlineData->code          = $this->code;
            $airlineData->flightDate    = $this->flightDate;
                //flightLegs
                $airlineFlightLegs = array();
                foreach ($this->flightLegs as $flightLeg) 
                {
                    $airlineFlightLeg = new AirlineFlightLeg();
                    $airlineFlightLeg->airlineClass     = $flightLeg['airlineClass'];
                    $airlineFlightLeg->arrivalAirport   = $flightLeg['arrivalAirport'];
                    $airlineFlightLeg->carrierCode      = $flightLeg['carrierCode'];
                    $airlineFlightLeg->date             = $flightLeg['date'];
                    $airlineFlightLeg->departureTime    = $flightLeg['departureTime'];
                    $airlineFlightLeg->fare             = $flightLeg['fare'];
                    $airlineFlightLeg->fareBasis        = $flightLeg['fareBasis'];
                    $airlineFlightLeg->flightNumber     = $flightLeg['flightNumber'];
                    $airlineFlightLeg->number           = $flightLeg['number'];
                    $airlineFlightLeg->originAirport    = $flightLeg['originAirport'];
                    $airlineFlightLeg->stopoverCode     = $flightLeg['stopoverCode'];

                    $airlineFlightLegs[]    = $airlineFlightLeg;
                }
                $airlineData->flightLegs    = $airlineFlightLegs;


            $airlineData->invoiceNumber = $this->invoiceNumber;
            $airlineData->isETicket     = $this->isETicket;
            $airlineData->isRegisteredCustomer  = $this->isRegisteredCustomer;
            $airlineData->isRestrictedTicket    = $this->isRestrictedTicket;
            $airlineData->isThirdParty  = $this->isThirdParty;
            $airlineData->issueDate     = $this->issueDate;
            $airlineData->merchantCustomerId    = $this->ad_merchantCustomerId;
            $airlineData->name          = $this->ad_name;
            $airlineData->passengerName = $this->passengerName;
            $airlineData->placeOfIssue  = $this->placeOfIssue;
            $airlineData->pnr           = $this->pnr;
            $airlineData->pointOfSale   = $this->pointOfSale;
            $airlineData->posCityCode   = $this->posCityCode;
            $airlineData->ticketDeliveryMethod  = $this->ticketDeliveryMethod;
            $airlineData->ticketNumber  = $this->ticketNumber;    //string (13)  The ticket or document number
        }
        //level3SummaryData: deprecated
        //numberOfInstallments
        //orderDate
        //typeInformation
            $typeInformation = new OrderTypeInformation();
                $typeInformation->purchaseType  = $this->purchaseType;
                $typeInformation->usageType     = $this->usageType;

        $additionalInput = new AdditionalOrderInput();

        $additionalInput->airlineData = $airlineData;
        //$additionalInfo->level3SummaryData = level3SummaryData;
        $additionalInput->numberOfInstallments   = $this->numberOfInstallments;
        $additionalInput->orderDate              = $this->orderDate;
        $additionalInput->typeInformation        = $typeInformation;


        /*-------------------------------------------------*/
        //        Set up the Amount of Money
        /*-------------------------------------------------*/
        $amountOfMoney = new AmountOfMoney();
            $amountOfMoney->amount          = $this->amount;
            $amountOfMoney->currencyCode    = $this->currency;

        /*-------------------------------------------------*/
        //        Set up the Customer
        /*-------------------------------------------------*/
        $customer = $this->buildCustomer();

        /*-------------------------------------------------*/
        //        Set up the Items
        /*-------------------------------------------------*/
        $itemsArray = array();
        foreach ($this->items as $item) {
            $newItem = new LineItem();
            $newItem->amount = $item['a_amount'];
            $newItem->currencyCode = $item['a_currencyCode'];
            $newItem->description = $item['i_description'];
            $newItem->merchantLinenumber = $item['i_merchantLinenumber'];
            $newItem->merchantPagenumber = $item['i_merchantPagenumber'];
            $newItem->nrOfItems = $item['i_nrOfItems'];
            $newItem->pricePerItem = $item['i_pricePerItem'];
            //$newItem->level3InterchangeInformation = $item['level3InterchangeInformation']; //deprecated
            $newItem->discountAmount = $item['o_discountAmount'];
            $newItem->lineAmountTotal = $item['o_lineAmountTotal'];
            $newItem->productCode = $item['o_productCode'];
            $newItem->productPrice = $item['o_productPrice'];
            $newItem->productType = $item['o_productType'];
            $newItem->quantity = $item['o_quantity'];
            $newItem->taxAmount = $item['o_taxAmount'];
            $newItem->unit = $item['o_unit'];

            $itemsArray[] = $newItem;
        }


        /*-------------------------------------------------*/
        //        Set up the references
        /*-------------------------------------------------*/
        $references = new OrderReferences();
            $references->descriptor         = $this->descriptor;
            // invoiceData
                $invoiceData = new OrderInvoiceData();
                $invoiceData->additionalData    = $this->i_additionalData;
                $invoiceData->invoiceDate       = $this->i_invoiceDate;
                $invoiceData->invoiceNumber     = $this->i_invoiceNumber;
                $invoiceData->textQualifiers    = $this->i_textQualifiers;
            $references->invoiceData    = $invoiceData;
            $references->merchantOrderId    = $this->merchantOrderId;
            $references->merchantReference  = $this->merchantReference;
        

        /*-------------------------------------------------*/
        //        Set up the shoppingCart
        /*-------------------------------------------------*/

        $shoppingCart = array();
        foreach ($this->amountBreakdown as $value)
        {
            $amountBreakdown = new AmountBreakdown();
            $amountBreakdown->amount    = $value['amount'];
            $amountBreakdown->type      = $value['type'];

            $shoppingCart[]  = $amountBreakdown;
        }


        $order = new Order();
            $order->amountOfMoney   = $amountOfMoney;
            $order->customer        = $customer;
            $order->references      = $references;
            $order->additionalInput = $additionalInput;
            $order->items           = $itemsArray;
            $order->shoppingCart    = $shoppingCart;

        return $order;
    }

    /**
    * Set Fraud. 
    *   More info at https://epayments.developer-ingenico.com/documentation/api/server/#schema_FraudFields
    * @return void
    */
    public function buildFraud()
    {
        
        $fraud = new FraudFields();
            $fraud->addressesAreIdentical   = $this->addressesAreIdentical;
            $fraud->blackListData       = $this->blackListData;
            //cardOwnwerAddres
                $cardOwnerAddress = new Address();
                    $cardOwnerAddress->additionalInfo   = $this->co_additionalInfo;
                    $cardOwnerAddress->city             = $this->co_city;
                    $cardOwnerAddress->countryCode      = $this->co_countryCode;
                    $cardOwnerAddress->houseNumber      = $this->co_houseNumber;
                    $cardOwnerAddress->state            = $this->co_state;
                    $cardOwnerAddress->stateCode        = $this->co_stateCode;
                    $cardOwnerAddress->street           = $this->co_street;
                    $cardOwnerAddress->zip              = $this->co_zip;
            $fraud->cardOwnerAddress    = $cardOwnerAddress;

            $fraud->customerIpAddress   = $this->customerIpAddress;
            $fraud->defaultFormFill     = $this->defaultFormFill;
            $fraud->fingerPrintActivated= $this->fingerPrintActivated;
            $fraud->giftCardType        = $this->giftCardType;
            $fraud->giftMessage         = $this->giftMessage;
            $fraud->hasForgottenPwd     = $this->hasForgottenPwd;
            $fraud->hasPassword         = $this->hasPassword;
            $fraud->isPreviousCustomer  = $this->isPreviousCustomer;
            $fraud->orderTimezone       = $this->orderTimezone;
            $fraud->shipComments        = $this->shipComments;
            $fraud->shipmentTrackingNumber  = $this->shipmentTrackingNumber;

            //shippingDetails
                $shippingDetails = new FraudFieldsShippingDetails();
                    $shippingDetails->methodDetails  = $this->methodDetails;
                    $shippingDetails->methodSpeed    = $this->methodSpeed;
                    $shippingDetails->methodType     = $this->methodType;
            $fraud->shippingDetails     = $shippingDetails;

            $fraud->userData            = $this->userData;
            $fraud->website             = $this->website;
        return $fraud;
    }

    /**
    * Set HostedCheckoutSpecificInput
    *   More info at https://epayments.developer-ingenico.com/documentation/api/server/#schema_FraudFields
    * @return void
    */
    public function buildHostedCheckout($url=null)
    {
        $hostedCheckoutSpecificInput = new HostedCheckoutSpecificInput();
        $hostedCheckoutSpecificInput->locale    = $this->locale;
        $hostedCheckoutSpecificInput->returnUrl = $url==null || $url==''? $this->returnUrl : $url;
        
        $hostedCheckoutSpecificInput->showResultPage = $this->showResultPage;
        $hostedCheckoutSpecificInput->tokens         = $this->tokens;
        $hostedCheckoutSpecificInput->variant   = $this->variant;

        // filters
            $filter = new PaymentProductFiltersHostedCheckout();
                $restrict = new PaymentProductFilter();
                if (is_array($this->restrictToProducts))
                    $restrict->products = $this->restrictToProducts;
                if (is_array($this->restrictToGroups))
                    $restrict->groups = $this->restrictToGroups;
                $exclude = new PaymentProductFilter();
                if (is_array($this->excludeProducts))
                    $exclude->products  = $this->excludeProducts;
                if (is_array($this->excludeGroups))
                    $exclude->groups  = $this->excludeGroups;
            $filter->restrictTo = $restrict;
            $filter->exclude    = $exclude;
            $filter->tokensOnly = $this->tokensOnly;
        $hostedCheckoutSpecificInput->paymentProductFilters = $filter;
        
        return $hostedCheckoutSpecificInput;
    }

}