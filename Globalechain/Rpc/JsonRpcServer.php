<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc;

use Exception;
use Globalechain\Rpc\Exception\RpcException;
use Globalechain\Rpc\Vendor\Audit\Auditable;
use Globalechain\Rpc\Vendor\Audit\JsonRpcCall;
use Zend\Json\Server\Server;
use Zend\Server\Method\Definition;

/**
 * Description of JsonRpcServer
 *
 * @author xieyihong
 */
class JsonRpcServer extends Server
{

    //put your code here
    private $auditer;

    public function __construct(Auditable $auditer)
    {
        parent::__construct();
        // Initialize the auditer
        $this->auditer = $auditer;
    }

    protected function _dispatch(Definition $invokable, array $params)
    {
        $callback = $invokable->getCallback();
        $jsonRpcCall = new JsonRpcCall($callback->getClass(), $callback->getMethod(), $params);
        $this->auditer->startAudit($jsonRpcCall);
        try {
            $result = parent::_dispatch($invokable, $params);
            $this->auditer->endAudit($jsonRpcCall);
            return $result;
        } catch (Exception $e) {
            $this->auditer->endAudit($jsonRpcCall);
            throw new RpcException($e->getMessage());
        }
    }

}
