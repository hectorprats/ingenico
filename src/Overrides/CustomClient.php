<?php
/*
 * This class was auto-generated from the API references found at
 * https://epayments-api.developer-ingenico.com/s2sapi/v1/
 */
namespace Asanzred\Ingenico;

use Ingenico\Connect\Sdk\Client;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\CommunicatorLogger;
use Ingenico\Connect\Sdk\Merchant;
use Ingenico\Connect\Sdk\Resource;


class CustomClient extends Resource
{
    const API_VERSION = 'v1';

    /** @var Communicator */
    private $communicator;

    /** @var string */
    private $clientMetaInfo;

    /**
     * Construct a new Ingenico ePayments platform server-to-server API client.
     *
     * @param CustomCommunicator $communicator
     * @param string $clientMetaInfo
     *
     */
    public function __construct(CustomCommunicator $communicator, $clientMetaInfo = '')
    {
        parent::__construct();
        //parent::parent::__construct();
        $this->communicator = $communicator;
        $this->setClientMetaInfo($clientMetaInfo);
        $this->context = array('apiVersion' => static::API_VERSION);
    }
    /**
     * @return Communicator
     */
    protected function getCommunicator()
    {
        return $this->communicator;
    }

    /**
     * @param CommunicatorLogger $communicatorLogger
     */
    public function enableLogging(CommunicatorLogger $communicatorLogger)
    {
        $this->getCommunicator()->enableLogging($communicatorLogger);
    }

    /**
     *
     */
    public function disableLogging()
    {
        $this->getCommunicator()->disableLogging();
    }

    /**
     * @param string $clientMetaInfo
     * @return $this
     */
    public function setClientMetaInfo($clientMetaInfo)
    {
        $this->clientMetaInfo = $clientMetaInfo ? base64_encode($clientMetaInfo) : '';
        return $this;
    }

    /**
     * @return string
     */
    protected function getClientMetaInfo()
    {
        return $this->clientMetaInfo;
    }

    /**
     * Resource /{merchantId}
     *
     * @param string $merchantId
     * @return Merchant
     */
    public function merchant($merchantId)
    {
        $newContext = $this->context;
        $newContext['merchantId'] = $merchantId;
        return new Merchant($this, $newContext);
    }
}
