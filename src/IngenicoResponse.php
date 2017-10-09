<?php
namespace Bardela\Ingenico;

use Ingenico\Connect\Sdk\Domain\Errors\ErrorResponse;
use Ingenico\Connect\Sdk\Domain\Hostedcheckout\CreateHostedCheckoutResponse;

class IngenicoResponse {
    protected $response;
    protected $status;
    protected $requestBody;
    /**
    * It sets up response and its status
    * @param ErrorResponse|CreateHostedCheckoutResponse Response
    * @param int $status Http status code
    * @param string|stdClass $requestBody JSON request body sent
    *
    * @return void
    */
    public function __construct($response, $status, $requestBody)
    {
        $this->response     = $response;
        $this->status       = $status;
        $this->requestBody  = json_decode($requestBody);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequestBody()
    {
        return $this->requestBody;
    }
    /**
    *
    * @param  Response
    * @return bool  Indicates wheter the request sent failed or succeed
    */
    public function responseFailed($response)
    {
        if ($response instanceof ErrorResponse)
        {
            return true;
        }
        elseif ($response instanceof CreateHostedCheckoutResponse)
        {
            return false;
        }
        else
        {
            throw new Exception("Unexpected response object class received in ".__FILE__. ":".__LINE__. " Class=".get_class($response), 1);
        }
    }
}