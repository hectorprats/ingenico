<?php 
//namespace ApiSW\Http\Controllers;
namespace Asanzred\Ingenico\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Config;
use Illuminate\Support\Facades\Input;

use Ingenico\Connect\Sdk\CommunicatorConfiguration;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\DefaultConnection;
use Ingenico\Connect\Sdk\Client;
use \Asanzred\Ingenico\IngenicoExample1;
use \Asanzred\Ingenico\IngenicoExample2;
use \Asanzred\Ingenico\IngenicoExample3;


class IngenicoController extends Controller
{
    protected $client;
    protected $basePath = '/ingenico';
    protected $returnUrlPath = '/ingenico/sample_return_url'; //the one that is defined in routes.php

    public function url($path=null)
    {
        $newUrl = url('/');
        $newUrl .= $path==null ? $this->returnUrlPath : $this->basePath . $path;
        return $newUrl;
    }
    /**
    * Returns settings array that can be use to initialize any Ingenico request
    * @param  string    $country country code. we will get its config
    * @return string[]  Array of settings
    */
    private function getParamsFor($country="default", $returnUrl=null)
    {
        $url = is_null($returnUrl) ? Config::get('ingenico.'.$country.'.return_url') : $returnUrl;
        $params = [
            'apikey'            => Config::get('ingenico.'.$country.'.api_key'),
            'secret'            => Config::get('ingenico.'.$country.'.secret_key'),
            'endpoint'          => Config::get('ingenico.'.$country.'.end_point'),
            'merchant'          => Config::get('ingenico.'.$country.'.merchant'),
            'base_redirect_url' => Config::get('ingenico.'.$country.'.base_redirect_url'),
            'return_url'        => $url,
        ];
        return $params;
    }

    /**
    * It tests the connection to Ingenido SDK for the desired country if specied
    * @return void
    */
    public function testConnection()
    {
        $country = Input::has('_country') ? Input::get('_country'):"default";
        $params = $this->getParamsFor($country);
        try {
            $testconnection = \Ingenico::testConnection($params);
            echo "Connection to Ingenico SDK was successful.<br />";
            dd($testconnection);
        } catch (\Exception $e) {
            echo "Failed to connect to Ingenico SDK ".$e->getMessage()."<br />";
            dd($e->getTrace());
        }
    }

    /**
    * This is the return from de redirect payment, process the result and make a new request
    * to know transaction status and indicates whether it was successful or not
    * 
    * It expects to have the following input structure coming from the redirect
    *    HTTP Response 201
    *    -RETURNMAC: String(50) When the consumer is returned to your site we will append this field and value to the query-string. You should store this data, so you can identify the returning consumer.
    *    -hostedCheckoutId: String(50) This is the ID under which the data for this checkout can be retrieved.
    *    -invalidTokens: array(4000)
    *    -partialRedirectUrl:
    *
    *  Then we make a new request to know the status using the hostedCheckoutId variable.
    *
    * @param  string    $country country code. we will get its config
    *
    */
    public function sampleReturnCheckout()
    {
        $returnMAC      = Input::get('RETURNMAC');
        $checkoutId     = Input::get('hostedCheckoutId');
        $isFramed       = Input::get('isFramed');
        $user_id        = Input::get('user_id');
        $lang           = Input::get('lang');

        $country = Input::has('_country') ? Input::get('_country'):"default";
        $params = $this->getParamsFor($country);

        try {
            $result = \Ingenico::getCheckoutStatus($params, $checkoutId);
            $response   = $result->getResponse();
            if (property_exists($response, 'errors'))
            {
                $eCategory  = $response->errors[0]->category;
                $msg        = $response->errors[0]->message;
                throw new \Exception($eCategory. ': '. $msg);
            }
        } catch(\Exception $e) {
            //the checkout most likely doesn't exist
            dd($e);
            $msg        = $e->getTrace()[0]['args'][1]->errors[0]->message;
            $eCategory  = $e->getTrace()[0]['args'][1]->errors[0]->category;
            $reqUrl     = $e->getTrace()[1]['args'][1];
            //dd($e->getTrace());
            echo $eCategory. ". ".$msg. " at ".$reqUrl;
            echo $e->getMessage();
            return;
        }
        //returnMac = fa34e6ba-d220-4fbe-bab0-f75a0e6db6b3
        if (!isset($response))
        {
            dd($e->getTrace());
            return 1;
        }
        //The hostedCheckoutId exists
        if (!$response->createdPaymentOutput)
        {
            //Session expire or not more details about it
            if ($response->status=="IN_PROGRESS")
            {
                echo "Payment status: IN PROGRESS. It's more likely to be a NEW transaction<br />";
            } elseif ($response->status=="PAYMENT_CREATED") {
                echo "Payment status: PAYMENT_CREATED<br />" ;
            }
        }
        else 
        {
            //Within the 2 hours session
            //$response->status=="PAYMENT_CREATED"
            $createdPaymentOutput   = $response->createdPaymentOutput;

            $paymentStatusCategory  = $createdPaymentOutput->paymentStatusCategory;
            $reference              = $createdPaymentOutput->paymentCreationReferences->additionalReference; //seteada en checkout por nosotros
            $payment                = $createdPaymentOutput->payment;
            $paymentId              = $payment->id;
            $paymentStatus          = $payment->status;
            if ($paymentStatusCategory == "SUCCESSFUL" && !in_array($paymentStatus, ['REVERSED','CHARGEBACKED','REFUNDED'])) 
            {
                echo "The transaction Succeed. status is $paymentStatusCategory ($paymentStatus)<br />";
            } 
            else 
            {
                echo "The transaction did not succeed status is $paymentStatusCategory ($paymentStatus) <br />";
            }

        }
        $paymentId = null;
        if (property_exists($response, 'createdPaymentOutput'))
        {
            //Within the 2 hours session
            $createdPaymentOutput   = $response->createdPaymentOutput;
            $payment                = $createdPaymentOutput->payment;
            $paymentId              = $payment->id;
        }
        if ($paymentId)
        {
            echo '<a href="/ingenico/sampleapprovepayment?paymentId='.$paymentId.'">Click for approving the payment '.$paymentId.'</a><br />';
            echo '<a href="/ingenico/samplecapture?paymentId='.$paymentId.'">Click for capture the payment '.$paymentId.'</a><br />';
        } else {
            echo 'The transaction has not payment info yet';
        }
        echo "The Transaction status full response is:<br />";
        dd($response);
    }

    /**
    * Executes the IngenicoExample1 and shows a button to the response url 
    * to  be redirected to the website
    * @param  string    $country country code. we will get its config
    */
    public function example1()
    {
        $returnUrl          = $this->url(); //replace for your own return url
        $example            = new IngenicoExample1();
        $attributesWrapper  = $example->mappedAttributes(); //instead of setData

        $country = Input::has('_country') ? Input::get('_country'):"default";
        $params = $this->getParamsFor($country, $returnUrl);
        //$result = \Ingenico::payment($params, $attributesWrapper, $returnUrl);
        $result = \Ingenico::payment($params, $attributesWrapper);
        dd($result);

        $response   = $result->getResponse();
        $status     = $result->getStatus();

        if ($status>=400)
        {
            $responseErrors = array();
            foreach ($response->errors as $key => $error)
            {
                $responseErrors[$key]['category'] = $error->category;
                $responseErrors[$key]['propertyName'] = $error->propertyName;
                $responseErrors[$key]['message'] = $error->message;
            }
            $requestBody = $result->getRequestBody();
            $responseErrors[]['requestBody'] = $requestBody;
            return $this->formatResponse($responseErrors, $status);
        }
        $requestBody = $result->getRequestBody();
        //else
        
        $baseRedirectUrl    = Config::get('ingenico.default.base_redirect_url');
        $redirectUrl = $baseRedirectUrl . $response->partialRedirectUrl;

        $responseSucceed = 'Sample 1: click on the following link to go to the payment gateway<br />';
        $responseSucceed .= '<a href="'.$redirectUrl.'">'.$redirectUrl.'</a>';
        return $this->formatResponse($responseSucceed, 200);
    }

    public function example2Request()
    {
        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'html'; //json or html
        $example2 = new IngenicoExample2();

        $fields = $example2->getInputFields();
        if ($typeReponse=="json")
        {
            return response()->json([
                'msg'       =>  'success',
                'url'       =>  $this->url('/exampleformresponse'),
                'fields'    =>  $fields
                ], 200
            );
        }
        else
        {
            return \View::make('asanzred/ingenico::sampleform', array(
                'url'       => $this->url('/exampleformresponse'),
                'fields'    => $fields
            ));
        }
    }

    /**
    * @param  string    $country country code. we will get its config
    */
    public function exampleFormResponse($country="default")
    {
        //remove notProperties elements from inputs array
        $notProperties = ['_token'=>'', '_countryConfig'];
        $inputs = array_diff_key(\Input::all(), $notProperties);
        
        $example            = new IngenicoExample2(); //It could be Example3, it does the same
        $attributesWrapper  = $example->mappedAttributes($inputs);

        $returnUrl  = $this->url();
        $country = Input::has('_country') ? Input::get('_country'):"default";
        $params = $this->getParamsFor($country, $returnUrl);

        //$result = \Ingenico::payment($attributesWrapper, $returnUrl); //@todo: remove
        $result = \Ingenico::payment($params, $attributesWrapper);


        $response   = $result->getResponse();
        
        if (property_exists($response, 'partialRedirectUrl'))
        {
            $baseRedirectUrl    = Config::get('ingenico.default.base_redirect_url');
            $redirectUrl = $baseRedirectUrl . $response->partialRedirectUrl;
            echo "Sample: click on the following link to go to the payment gateway<br />";
            echo '<a href="'.$redirectUrl.'">'.$redirectUrl.'</a>';
        }
        else
        {
            echo "<span>There was an Error while creating the Transaction";
            dd($response);
        }
        return;
    }

    public function example3Request()
    {
        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'html'; //json or html
        $example3 = new IngenicoExample3();

        $fields = $example3->getInputFields();
        if ($typeReponse=="json")
        {
            return response()->json([
                'msg'       =>  'success',
                'url'       =>  $this->url('/exampleformresponse'),
                'fields'    =>  $fields
                ], 200
            );
        }
        else
        {
            return \View::make('asanzred/ingenico::sampleform', array(
                'url'       => $this->url('/exampleformresponse'),
                'fields'    => $fields
            ));
        }
    }

    public function formatResponse($response, $status)
    {

        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'html'; //json or html
        if ($typeReponse=="json")
        {
            $msg = $status==200 ? 'success' : 'error';
            return response()->json([
                'msg'       =>  $msg,
                'result'    =>  $response
                ], $status
            );
        }
        else
        {
            return response($response, $status);
        }
    }

    public function approvePayment()
    {
        $paymentId = \Input::get('paymentId'); //920674871
        
        $response   = null;
        $country    = Input::has('_country') ? Input::get('_country'):"default";
        $params     = $this->getParamsFor($country);
        try{
            $result = \Ingenico::approvePayment($params, $paymentId);
            $response   = $result->getResponse();
            echo "SUCCESSFUL<br />";
        } catch (Exception $e){
            dd($e);
        }
        dd($response);
    }
}
