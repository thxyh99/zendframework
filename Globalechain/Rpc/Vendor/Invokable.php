<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Vendor\Audit;

/**
 *
 * @author xieyihong
 */
interface Invokable
{

    //put your code here
    public function getClass();

    public function getMethod();

    public function getParams();
}
