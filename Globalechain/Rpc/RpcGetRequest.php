<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc;

use Globalechain\Rpc\Vendor\Document\DocumentManager;
use Zend\Json\Server\Server;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of RpcGetRequest
 *
 * @author xieyihong
 */
class RpcGetRequest
{

    //put your code here
    protected $server;
    protected $apiConfig;
    protected $serviceLocator;

    public function getServer()
    {
        return $this->server;
    }

    public function getApiConfig()
    {
        return $this->apiConfig;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServer(Server $server)
    {
        $this->server = $server;
        return $this;
    }

    public function setApiConfig(array $apiConfig)
    {
        $this->apiConfig = $apiConfig;
        return $this;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function run()
    {
        //Display all current access interfaces
        $documentMananger = new DocumentManager();
        $documentMananger->rpcDocument($this);
    }

}
