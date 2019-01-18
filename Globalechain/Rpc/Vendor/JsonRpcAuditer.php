<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Vendor\Audit;

/**
 * Description of JsonRpcAuditer
 *
 * @author xieyihong
 */
class JsonRpcAuditer implements Auditable
{

    private $serviceManager;
    private $type;
    private $apiAccessRecord;

    public function __callStatic($serviceManager, $type = false)
    {
        $this->type = $type;
        $this->serviceManager = $serviceManager;
    }

    //put your code here
    public function endAudit(Invokable $invokable)
    {
        $this->apiAccessRecord->endRecords($this->serviceManager);
    }

    public function startAudit(Invokable $invokable)
    {
        $this->apiAccessRecord->startRecords();
        $this->apiAccessRecord->setParams($invokable->getClass(), $invokable->getMethod(), $invokable->getParams());
    }

}
