<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Vendor\Audit;

/**
 * Description of JsonRpcCall
 *
 * @author xieyihong
 */
class JsonRpcCall implements Invokable
{

    //put your code here
    private $class;
    private $method;
    private $params;

    public function __construct($class, $method, $params)
    {
        $this->class = $class;
        $this->method = $method;
        $this->params = $params;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParams()
    {
        return $this->params;
    }

}
