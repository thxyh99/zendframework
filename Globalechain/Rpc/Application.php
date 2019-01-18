<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc;

use Globalechain\Rpc\Api\ApiProviderInterface;
use Globalechain\Rpc\Exception\RpcException;
use Zend\Json\Server\Server;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of Application
 *
 * @author xieyihong
 */
class Application
{

    //put your code here
    protected $serviceManager;
    private $server = null;
    private $apiConfig;

    public function __construct($applicationConfig)
    {
        // Create service manager
        $serviceManagerConfig = isset($applicationConfig['service_manager']) ? $applicationConfig['service_manager'] : array();
        $this->serviceManager = new ServiceManager(new ServiceManagerConfig($serviceManagerConfig));
        $this->serviceManager->setService('applicationConfig', $applicationConfig);
        // Load modules
        $this->loadModules();
    }

    private function loadModules()
    {
        $moduleManager = $this->serviceManager->get('ModuleManager');
        $moduleManager->loadModules();
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getApiConfig()
    {
        return $this->apiConfig;
    }

    public function getService()
    {
        if (is_null($this->server)) {
            // Create JsonRpc server
            $this->server = new Server();
//            $this->server = 
            $auditer = new Vendor\Audit\JsonRpcAuditer($this->serviceManager,true);
            
        }
        return $this->server;
    }

    public function run()
    {
        // Get json server
        $this->server = $this->getService();
        // Params init
        $applicationParameter = new ApplicationParameter();
        try {
            $applicationParameter->initByRequest($this->server->getRequest(), $this->apiConfig);
        } catch (\Exception $e) {
            throw new RpcException($e->getMessage());
        }

        if (empty($applicationParameter->getType()) || !class_exists($applicationParameter->getClass()) || in_array($applicationParameter->getMethod(), $applicationParameter->getDisabledMethods())) {
            throw new RpcException('API parameter access error for RPC service');
        }

        $apiName = $applicationParameter->getClass();
        $apiClass = new $apiName($this->serviceManager);

        if (!($apiClass instanceof ApiProviderInterface)) {
            throw new RpcException('The API of the RPC service must implement the ApiProviderInterface interface');
        }

        if (!method_exists($apiClass, $applicationParameter->getMethod())) {
            throw new RpcException('API method for RPC service does not exist');
        }

        $reflection = new \ReflectionMethod($apiClass, $applicationParameter->getMethod());
        $numberOfRequiredParameters = $reflection->getNumberOfRequiredParameters();   //Access method must have a number of parameters
        $requestParamsNargs = $this->server->getRequest()->getParams() ? count($this->server->getRequest()->getParams()) : 0;  //Number of request method parameters
        if ($numberOfRequiredParameters > $requestParamsNargs) {
            throw new RpcException(sprintf('%s params must is %d but you is %d', $this->server->getRequest()->getMethod(), $numberOfRequiredParameters, $requestParamsNargs));
        }

        $this->server->getRequest()->setMethod($applicationParameter->getMethod());
        $this->server->setClass($apiClass);

        //Handling POST requests
        $this->server->handle();
    }

}
