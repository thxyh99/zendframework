<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Exception;

/**
 * Description of Exception
 *
 * @author xieyihong
 */
abstract class Exception extends \Exception implements ExceptionInterface
{

    //put your code here
    public function __construct($message, $code, $previous)
    {
        parent::__construct($message, $code, $previous);
    }

}
