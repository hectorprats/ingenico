<?php
namespace Asanzred\Ingenico;

use Config;
use Asanzred\Ingenico\IngenicoAttributtesWrapper;

/**
* This example can be executed in 2 steps
* First: get the all inputs and render in html form (we can modify the field values we want)
* 
*       $example            = new IngenicoExample2();
*       $inputs2Render      = $example2->getInputFields();
*
* After the sending the form, catch the form values
*
*       $attributesWrapper  = $example->mappedAttributes($inputs);
*       $result     = \Ingenico::payment($attributesWrapper, 'http://returnurl...');
*/
class IngenicoExample2 extends IngenicoExampleGeneric
{
    // inherit:
    //public function mappedAttributes($inputFields=null)

    /**
    * {@inheritDoc}
    *
    * @return string[string] Array of fields
    */
    public function getDefaultData()
    {
        return null;
    }

    /**
    * {@inheritDoc}
    *
    * @return string[string][int][string] Array of input fields
    */
    public function getInputFields()
    {
        /**
        * @var string[string][int][string]
        * Array of inputs organized by groups
        *
        * $inputs['FIELDGROUPNAME']                 array   Group of inputs
        *                          [int]            array   Input data
        *                               ['type']    string  Type of input. values text|select
        *                               ['name']    string  Input name
        *                               ['value']   string  Input value (only for type=text)
        *                               ['options'] array   Option values (only for type=select)
        */
        $inputs = array();

        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'locale', 'value' => 'en_GB']; //ISO 2
        //too many fields for airlineData
        //$inputs['order-AdditionalOrderInput-airlineData'] = ['type' => 'text', 'name' => '', 'value' => ''];
        //deprecated
        //$inputs['order-AdditionalOrderInput-level3SummaryData'] = ['type' => 'text', 'name' => '', 'value' => ''];
        $inputs['order-additionalOrderInput'][] = ['type' => 'text', 'name' => 'numberOfInstallments', 'value' => ''];
        $inputs['order-additionalOrderInput'][] = ['type' => 'text', 'name' => 'orderDate', 'value' => ''];

        $inputs['order-additionalOrderInput-typeInformation'][] = ['type' => 'select', 'name' => 'purchaseType', 'options' => ['good','service']];
        $inputs['order-additionalOrderInput-typeInformation'][] = ['type' => 'select', 'name' => 'usageType', 'options' => ['private','commercial']];

        $inputs['order-amountOfMoney'][] = ['type' => 'text', 'name' => 'amount', 'value' => '10100']; //101€
        $inputs['order-amountOfMoney'][] = ['type' => 'text', 'name' => 'currency', 'value' => 'USD'];

        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_countryCode', 'value' => 'US']; //ISO 2
        
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_state',   'value' => 'California'];
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_city',    'value' => 'Oakland'];
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_zip',     'value' => '0001'];
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_street',  'value' => 'Main Square'];
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_houseNumber', 'value' => '1'];
        $inputs['customer-billingAddress'][] = ['type' => 'text', 'name' => 'bi_additionalInfo', 'value' => 'b'];
        //Company Information
        $inputs['customer-companyInformation'][] = ['type' => 'text', 'name' => 'ci_name', 'value' => 'SW'];
        //Contact Details
        $inputs['customer-contactDetails'][] = ['type' => 'text', 'name' => 'cd_emailAddress', 'value' => 'test@testemail.com'];
        $inputs['customer-contactDetails'][] = ['type' => 'text', 'name' => 'cd_emailMessageType', 'value' => 'plain-text'];
        $inputs['customer-contactDetails'][] = ['type' => 'text', 'name' => 'cd_faxNumber',   'value' => '0199999998'];
        $inputs['customer-contactDetails'][] = ['type' => 'text', 'name' => 'cd_phoneNumber', 'value' => '0199999999'];
        $inputs['customer'][] = ['type' => 'text', 'name' => 'fiscalNumber', 'value' => 'Oakland'];
        $inputs['customer'][] = ['type' => 'text', 'name' => 'c_locale', 'value' => ''];
        $inputs['customer'][] = ['type' => 'text', 'name' => 'merchantCustomerId', 'value' => '987654'];

        //Personal Information
        $inputs['customer-personalInformation'][] = ['type' => 'text', 'name' => 'dateOfBirth', 'value' => '19800116'];
        $inputs['customer-personalInformation'][] = ['type' => 'select', 'name' => 'gender', 'options' => ['male','female']];
        $inputs['customer-personalInformation'][] = ['type' => 'text', 'name' => 'pi_firstName', 'value' => 'Juan'];
        $inputs['customer-personalInformation'][] = ['type' => 'text', 'name' => 'pi_surname', 'value' => 'Palomo'];
        $inputs['customer-personalInformation'][] = ['type' => 'text', 'name' => 'pi_surnamePrefix', 'value' => ''];
        $inputs['customer-personalInformation'][] = ['type' => 'text', 'name' => 'pi_title', 'value' => 'Mr'];

        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_additionalInfo', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_city', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_countryCode', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_houseNumber', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_firstName', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_surname', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_surnamePrefix', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_title', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_state', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_stateCode', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_street', 'value' => ''];
        $inputs['customer-shippingAddress'][] = ['type' => 'text', 'name' => 'sa_zip', 'value' => ''];

        $inputs['customer'][] = ['type' => 'text', 'name' => 'vatNumber', 'value' => '0006677'];
        
        //items is array. we do not set arrays
        //$inputs['order-items'][] = ['type' => 'text', 'name' => '', 'value' => ''];
        //descriptor not supported
        //$inputs['order-references'][] = ['type' => 'text', 'name' => 'descriptor', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'i_additionalData', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'i_invoiceDate', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'i_invoiceNumber', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'i_textQualifiers', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'merchantOrderId', 'value' => ''];
        $inputs['order-references'][] = ['type' => 'text', 'name' => 'merchantReference', 'value' => '555666777'];
        //shoppingCart is an array
        //$inputs['order-shoppingCart'][] = ['type' => 'text', 'name' => '', 'value' => ''];


        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'select', 'name' => 'isRecurring', 'options' => ['false','true']];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'variant', 'value' => ''];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'select', 'name' => 'showResultPage', 'options' => ['true','false']];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'tokens', 'value' => ''];
        /*
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'variant', 'value' => '']; = ['type' => 'text', 'name' => 'excludeGroups', 'value' => ''];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'variant', 'value' => '']; = ['type' => 'text', 'name' => 'excludeProducts', 'value' => ''];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'variant', 'value' => '']; = ['type' => 'text', 'name' => 'restrictToGroups', 'value' => ''];
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'text', 'name' => 'variant', 'value' => '']; = ['type' => 'text', 'name' => 'restrictToProducts', 'value' => ''];
        */
        $inputs['hostedCheckoutSpecificInput'][] = ['type' => 'select', 'name' => 'tokensOnly', 'options' => ['false','true']];

        //FRAUD        
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'addressesAreIdentical', 'options' => ['false','true']];
        $inputs['fraud'][] = ['type' => 'text', 'name' => 'blackListData', 'value' => ''];
        //$cardOwnerAddress; It doesn't have sense as the it's redirect payment method
        //$inputs['fraud'][] = ['type' => 'text', 'name' => 'cardOwnerAddress', 'value' => ''];
        $inputs['fraud'][] = ['type' => 'text', 'name' => 'customerIpAddress', 'value' => '192.168.1.1'];
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'defaultFormFill', 'options' => ['automatically','automatically-but-modified','manually ']];
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'fingerPrintActivated', 'options' => ['false','true']];
        //$giftCardType           = "birthday";
        //$giftMessage            = "";
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'hasForgottenPwd', 'options' => ['false','true']];
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'hasPassword', 'options' => ['false','true']];
        $inputs['fraud'][] = ['type' => 'select', 'name' => 'isPreviousCustomer', 'options' => ['false','true']];
        $inputs['fraud'][] = ['type' => 'text', 'name' => 'orderTimezone', 'value' => '']; //ISO 3166 2
        $inputs['fraud'][] = ['type' => 'text', 'name' => 'shipComments',  'value' => ''];

        $inputs['fraud-shipDetails'][] = ['type' => 'text', 'name' => 'methodDetails',    'value' => ''];
        $inputs['fraud-shipDetails'][] = ['type' => 'text', 'name' => 'methodSpeed',    'value' => ''];
        $inputs['fraud-shipDetails'][] = ['type' => 'text', 'name' => 'methodType',    'value' => ''];
        //useData is an array
        //$inputs['fraud'][] = ['type' => 'text', 'name' => 'userData', 'value' => ''];
        $inputs['fraud'][] = ['type' => 'text', 'name' => 'website', 'value' => ''];
        return $inputs;
    }
    
}