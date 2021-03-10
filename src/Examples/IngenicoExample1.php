<?php
namespace Smallworldfs\Ingenico\Examples;

use Config;
use Smallworldfs\Ingenico\Classes\IngenicoAttributtesWrapper;

/**
* Sample data 1 for test:
* How to test this sample
* 
*        $example            = new IngenicoExample1();
*        $attributesWrapper  = $example->mappedAttributes(); //instead of setData
*        $result     = \Ingenico::payment($attributesWrapper, 'http://returnurl...');
*
*/
class IngenicoExample1 extends IngenicoExampleGeneric
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
        $inputs = array();
        $inputs['locale']     = "en_GB";
        //billingAddress
        $inputs['bi_countryCode']    = "US"; //ISO 2
        $inputs['bi_state']      = "California";
        $inputs['bi_city']       = "Los Angeles";
        $inputs['bi_zip']        = "0001";
        $inputs['bi_street']     = "Rodeo Road";
        $inputs['bi_houseNumber']    = "1";
        $inputs['bi_additionalInfo'] = "b";
        //companyInformation
        $inputs['ci_name'] = "SW";

        $inputs['cd_emailAddress']       = "johndoe@test.com";
        $inputs['cd_emailMessageType']   = "plain-text";
        $inputs['cd_faxNumber']          = "0199999998";
        $inputs['cd_phoneNumber']        = "0199999999";

        $inputs['fiscalNumber']   = "7777666";

        $inputs['merchantCustomerId'] = "0000123";

        $inputs['dateOfBirth']    = "19601230";
        $inputs['gender']         = "male";
            //personalInformation
            $inputs['pi_firstName']      = "John";
            $inputs['pi_surname']        = "Doe"; //Required
            $inputs['pi_surnamePrefix']  = "";
            $inputs['pi_title']          = "Mr.";
        //shippingAddress
        $inputs['sa_countryCode']    = "US"; //ISO 2
        $inputs['sa_state']      = "California";
        $inputs['sa_city']       = "Los Angeles";
        $inputs['sa_zip']        = "0001";
        $inputs['sa_street']     = "Rodeo Road";
        $inputs['sa_houseNumber']    = "1";
        $inputs['sa_additionalInfo'] = "b";

        $inputs['vatNumber']    = "3366644477";

        //amountOfMoney
        $inputs['amount']     = 9900; //99
        $inputs['currency']   = "USD";

        $inputs['merchantReference']  = "123456";

        return $inputs;
    }


    /**
    * {@inheritDoc}
    *
    * @return string[string][int][string] Array of input fields
    */
    public function getInputFields()
    {
        return null;
    }

}