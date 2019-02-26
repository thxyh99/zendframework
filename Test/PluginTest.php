<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test;

define('ROOT_PATH', __DIR__);
require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Service\Plugin;

/**
 * Description of PluginTest
 *
 * @author xieyihong
 */
class PluginTest extends TestCase
{

    //put your code here
    public function testAbs()
    {
        $plugin = new Plugin();
        $this->assertEquals(4, $plugin->abs(4));
        $this->assertEquals(4, $plugin->abs(-4));
    }

    public function testSum()
    {
        $plugin = new Plugin();
        $this->assertEquals('3.5', $plugin->sum('1.2', '2.3'));
        $this->assertEquals(4, $plugin->sum(1, 2));
    }

}
