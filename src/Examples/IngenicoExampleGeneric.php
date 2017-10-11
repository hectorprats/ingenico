<?php
namespace Bardela\Ingenico;

use Bardela\Ingenico\IngenicoAttributtesWrapper;

/**
* The main goal of this class is show and manipulate the collections of IngenicoAttributtesWrapper properties
* It's a generic/abstact class, so we can extend it to create a custom set of data for each test
*
*/
abstract class IngenicoExampleGeneric
{

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
    public function mappedAttributes($inputFields=null)
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
        return $ingenicoAttributesWrapper;
    }
}