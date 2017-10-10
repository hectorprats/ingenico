<?php 
//namespace ApiSW\Http\Controllers;
namespace Bardela\Ingenico;
 
use ApiSW\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Config;
use Illuminate\Support\Facades\Input;

use Ingenico\Connect\Sdk\CommunicatorConfiguration;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\DefaultConnection;
use Ingenico\Connect\Sdk\Client;
use \Bardela\Ingenico\IngenicoExample1;


class IngenicoController extends Controller
{
    protected $client;

    /*
    * It tests the connection to Ingenido SDK with the config values from file
    * @return void
    */
    public function testConnection()
    {
        $ingenicoRequest = new IngenicoRequest();
        try {
            $testconnection = $ingenicoRequest->testConnection();
            echo "Connection to Ingenico SDK was successful.<br />";
            dd($testconnection);
        } catch (\Exception $e) {
            echo "Failed to connect to Ingenico SDK ".$e->getMessage()."<br />";
            dd($e->getTrace());
        }
    }

    /*
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
    */
    public function sampleReturnCheckout()
    {
        $returnMAC      = Input::get('RETURNMAC');
        $checkoutId     = Input::get('hostedCheckoutId');
        $isFramed       = Input::get('isFramed');
        $user_id        = Input::get('user_id');
        $lang           = Input::get('lang');

        $merchantId = Config::get('ingenico.merchant');

        $ingenicoRequest    = new IngenicoHostedCheckoutRequest();

        try {
            $response = $ingenicoRequest->getStatus($checkoutId);
        } catch(\Exception $e) {
            //the checkout most likely doesn't exist
            $msg        = $e->getTrace()[0]['args'][1]->errors[0]->message;
            $eCategory  = $e->getTrace()[0]['args'][1]->errors[0]->category;
            $reqUrl     = $e->getTrace()[1]['args'][1];
            //dd($e->getTrace());
            echo $eCategory. ". ".$msg. " at ".$reqUrl;
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
        echo "The Transaction status full response is:<br />";
        dd($response);
    }

    /*
    * Executes the IngenicoExample1 and shows a button to the response url 
    * to  be redirected to the website
    */
    public function example1()
    {
        $returnUrl  = 'http://api.sw.local/v1/ingenico/sample_return_url'; //replace for your own return url
        $example    = new IngenicoExample1($returnUrl);
        $result     = $example->run();
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
        
        $baseRedirectUrl    = Config::get('ingenico.base_redirect_url');
        $redirectUrl = $baseRedirectUrl . $response->partialRedirectUrl;

        $responseSucceed[] = 'Sample 1: click on the following link to go to the payment gateway<br />';
        $responseSucceed[] = '<a href="'.$redirectUrl.'">'.$redirectUrl.'</a>';
        return $this->formatResponse($responseSucceed, 200);
    }

    public function example2Request()
    {
        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'json'; //json or html
        $example2 = new IngenicoExample2();

        $fields = $example2->getInputFields();
        if ($typeReponse=="html")
        {
            return \View::make('bardela/ingenico::sampleform', array(
                'url'       => '/v1/ingenico/example2response',
                'fields'    => $fields
            ));
        }
        else
        {
            return response()->json([
                'msg'       =>  'success',
                'url'       => '/v1/ingenico/example2response',
                'fields'    =>  $fields
                ], 200
            );
        }
    }

    public function example2Response()
    {
        $allInputs = \Input::all();
        $notProperties = ['_token'=>''];
        //remove notProperties elements from inputs
        $inputs = array_diff_key($allInputs, $notProperties);

        $returnUrl  = 'http://api.sw.local/v1/ingenico/sample_return_url';
        $example2   = new IngenicoExample2($returnUrl);
        $result     = $example2->run($inputs);
        $response   = $result->getResponse();
        
        //echo "The Checkout Hosted Request returned:<br />";
        //var_dump($response);

        $baseRedirectUrl    = Config::get('ingenico.base_redirect_url');

        $redirectUrl = $baseRedirectUrl . $response->partialRedirectUrl;
        echo "Sample 2: click on the following link to go to the payment gateway<br />";
        echo '<a href="'.$redirectUrl.'">'.$redirectUrl.'</a>';
        return;
    }

    public function example3Request()
    {
        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'json'; //json or html
        $example3 = new IngenicoExample3();

        $fields = $example3->getInputFields();
        if ($typeReponse=="html")
        {
            return \View::make('bardela/ingenico::sampleform', array(
                'url'       => '/v1/ingenico/example3response',
                'fields'    => $fields
            ));
        }
        else
        {
            return response()->json([
                'msg'       =>  'success',
                'url'       => '/v1/ingenico/example3response',
                'fields'    =>  $fields
                ], 200
            );
        }
    }

    public function example3Response()
    {
        $allInputs = \Input::all();
        $notProperties = ['_token'=>''];
        //remove notProperties elements from inputs
        $inputs = array_diff_key($allInputs, $notProperties);

        $returnUrl  = 'http://api.sw.local/v1/ingenico/sample_return_url';
        $example3   = new IngenicoExample3($returnUrl);
        $result     = $example3->run($inputs);
        $response   = $result->getResponse();
        
        //echo "The Checkout Hosted Request returned:<br />";
        //var_dump($response);

        $baseRedirectUrl    = Config::get('ingenico.base_redirect_url');

        $redirectUrl = $baseRedirectUrl . $response->partialRedirectUrl;
        echo "Sample 3: click on the following link to go to the payment gateway<br />";
        echo '<a href="'.$redirectUrl.'">'.$redirectUrl.'</a>';
        return;
    }

    public function formatResponse($arrayResponse, $status)
    {

        $typeReponse = Input::get('type_response') ? Input::get('type_response') : 'json'; //json or html
        if ($typeReponse=="html")
        {
            $res    = var_dump($arrayResponse);
            return response($res, $status);
        }
        else
        {
            $msg = $status==200 ? 'success' : 'error';
            return response()->json([
                'msg'       =>  $msg,
                'result'    =>  $arrayResponse
                ], $status
            );
        }
    }
}
