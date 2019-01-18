<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc;

use Globalechain\Rpc\Exception\RpcException;
use Zend\Json\Server\Request;

/**
 * Description of ApplicationParameter
 *
 * @author xieyihong
 */
class ApplicationParameter
{

    //put your code here
    private $method;
    private $class;
    private $disabledMethods = array();
    private $type = true;
    static public $methodRegex = '/^[a-zA-Z]+\.[a-zA-Z]+$/';

    public function initByRequest(Request $request, array $apiConfig)
    {
        if (!preg_match(self::$methodRegex, $request->getMethod())) {
            throw new RpcException('params method error right for example : (test.test)');
        }

        $parameterArray = explode('.', $request->getMethod());

        if (isset($parameterArray[0]) && isset($apiConfig[$parameterArray[0]])) {
            $this->initByConfig($apiConfig[$parameterArray[0]]);
        }
        if (isset($parameterArray[1])) {
            $this->setMethod($parameterArray[1]);
        }
    }

    public function initByConfig(array $config)
    {
        $iniMoudelConfig = array(
            'type' => $this->getType(),
            'class' => $this->getClass(),
            'disabledMethods' => $this->getDisabledMethods(),
        );

        $moudelConfig = array_intersect_key($config, $iniMoudelConfig);

        foreach ($moudelConfig as $option => $value) {
            $methodName = 'set' . ucfirst($option);
            $this->{$methodName}($value);
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getDisabledMethods()
    {
        return $this->disabledMethods;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function setDisabledMethods($disabledMethods)
    {
        $this->disabledMethods = $disabledMethods;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}
