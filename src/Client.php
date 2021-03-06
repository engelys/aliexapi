<?php
namespace AliexApi;

use AliexApi\Config\ConfigInterface;
use AliexApi\Operations\OperationInterface;
use AliexApi\Request\RequestFactory;
use AliexApi\ResponseTransformer\ResponseTransformerFactory;

class Client{

    protected $config;

    public function __construct(ConfigInterface $config = null){
        $this->config = $config;
    }

    public function runOperation(OperationInterface $operation, ConfigInterface $config = null)
    {
        $config = is_null($config) ? $this->config: $config;

        if(true === is_null($config)){
            throw new \Exception("No configuration passed. ");
        }

        $requestObject = RequestFactory::createRequest($config);

        $response = $requestObject->perform($operation);

        return $this->applyResponseTransformer($response, $config);
    }

    protected function applyResponseTransformer($response, ConfigInterface $config)
    {
        if (true === is_null($config->getResponseTransformer())) {
            return $response;
        }

        $responseTransformer = ResponseTransformerFactory::createResponseTransformer($config);
        
        return $responseTransformer->transform($response);
    }
}