<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Api;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of AbstractApi
 *
 * @author xieyihong
 */
abstract class AbstractApi implements ApiProviderInterface
{

    //put your code here
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

}
