<?php
namespace Smallworldfs\Ingenico;

use Exception;
use Ingenico\Connect\Sdk\Communicator;
use Ingenico\Connect\Sdk\responseClassMap;
use Ingenico\Connect\Sdk\DataObject;
use Ingenico\Connect\Sdk\RequestObject;
use Ingenico\Connect\Sdk\CallContext;
//use Ingenico\Connect\Sdk\Connection;
//use Ingenico\Connect\Sdk\CommunicatorConfiguration;
//use Ingenico\Connect\Sdk\ResponseFactory;

use Ingenico\Connect\Sdk\CommunicatorConfiguration;
use Ingenico\Connect\Sdk\Connection;
/**
 * Override post method
 *
 */
class CustomCommunicator extends Communicator
{
    /** @var Connection */
    private $connection;

    /** @var CommunicatorConfiguration */
    private $communicatorConfiguration;

    /** @var ResponseFactory|null */
    private $responseFactory = null;

    /** @var ResponseExceptionFactory|null */
    private $responseExceptionFactory = null;

    /**
     * {@inheritDoc}
     * @param Connection $connection
     * @param CommunicatorConfiguration $communicatorConfiguration
     */
    public function __construct(
        Connection $connection,
        CommunicatorConfiguration $communicatorConfiguration
    ) {
        /*
        * Sets connection and communicator in both parent and child 
        * cause their properties are defined as private (non inheritance)
        */
        parent::__construct($connection, $communicatorConfiguration);
        $this->connection = $connection;
        $this->communicatorConfiguration = $communicatorConfiguration;
    }

    /**
     * {@inheritDoc}
     * @param ResponseClassMap $responseClassMap
     * @param string $relativeUriPath
     * @param string $clientMetaInfo
     * @param DataObject|null $requestBodyObject
     * @param RequestObject|null $requestParameters
     * @param CallContext $callContext
     * @return DataObject
     * @throws Exception
     */
    public function post(
        ResponseClassMap $responseClassMap,
        $relativeUriPath,
        $clientMetaInfo = '',
        DataObject $requestBodyObject = null,
        RequestObject $requestParameters = null,
        CallContext $callContext = null
    ) {
        $relativeUriPathWithRequestParameters =
            $this->getRelativeUriPathWithRequestParameters($relativeUriPath, $requestParameters);
        $requestHeaders =
            $this->getRequestHeaders('POST', $relativeUriPathWithRequestParameters, $clientMetaInfo, $callContext);
        $requestBody = $requestBodyObject ? $requestBodyObject->toJson() : '';
        $connectionResponse = $this->getConnection()->post(
            $this->communicatorConfiguration->getApiEndpoint() . $relativeUriPathWithRequestParameters,
            $requestHeaders,
            $requestBody,
            $this->communicatorConfiguration->getProxyConfiguration()
        );
        $response =
            $this->getResponseFactory()->createResponse($connectionResponse, $responseClassMap, $callContext);
        $httpStatusCode = $connectionResponse->getHttpStatusCode();

        /*
        * This block is different from the inherited method
        * Return a custom response that contains the response and the status
        *
        */
        $customResponse = new IngenicoResponse($response, $httpStatusCode, $requestBody);
        return $customResponse;
    }

    /**
     * {@inheritDoc}
     * @param ResponseClassMap $responseClassMap
     * @param string $relativeUriPath
     * @param string $clientMetaInfo
     * @param RequestObject|null $requestParameters
     * @param CallContext $callContext
     * @return DataObject
     * @throws Exception
     */
    public function get(
        ResponseClassMap $responseClassMap,
        $relativeUriPath,
        $clientMetaInfo = '',
        RequestObject $requestParameters = null,
        CallContext $callContext = null
    ) {
        $relativeUriPathWithRequestParameters =
            $this->getRelativeUriPathWithRequestParameters($relativeUriPath, $requestParameters);
        $requestHeaders =
            $this->getRequestHeaders('GET', $relativeUriPathWithRequestParameters, $clientMetaInfo, $callContext);

        $connectionResponse = $this->getConnection()->get(
            $this->communicatorConfiguration->getApiEndpoint() . $relativeUriPathWithRequestParameters,
            $requestHeaders,
            $this->communicatorConfiguration->getProxyConfiguration()
        );
        $response =
            $this->getResponseFactory()->createResponse($connectionResponse, $responseClassMap, $callContext);
        $httpStatusCode = $connectionResponse->getHttpStatusCode();
        /*
        * This block is different from the inherited method
        * Return a custom response that contains the response and the status
        *
        */
        $customResponse = new IngenicoResponse($response, $httpStatusCode, null);
        return $customResponse;
    }
}