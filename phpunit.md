# PHP单元测试框架PHPUnit的使用

**安装PHPUnit**

使用 composer 方式安装 PHPUnit

    composer require --dev phpunit/phpunit ^5.7

    "require-dev": {
    	"phpunit/phpunit": "^6.2"
    },

**PHPUnit用法**

- 单元测试类名必须以 Test 结尾，必须继承 `\PHPUnit\Framework\TestCase` 基类。
- 每个测试函数必须以 test 开头。

Plugin.php


    namespace Service;
    
    class Plugin
    {
    
	    public function abs($num)
	    {
	    	return abs($num);
	    }
	    
	    public function sum($numa, $numb)
	    {
	    	return $numa + $numb;
	    }
    
    }


PluginTest.php

    namespace Test;
    
    define('ROOT_PATH', __DIR__);
    require_once 'vendor/autoload.php';
    
    use PHPUnit\Framework\TestCase;
    use Service\Plugin;
    
    class PluginTest extends TestCase
    {
    
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


**命令行执行:**

phpunit 命令 测试文件命名

    xieyihong@7f-xiexuanh MINGW64 /e/thxyh99/xampp/htdocs/project/zendframework (master)
    $ ./vendor/bin/phpunit Test/PluginTest.php
    PHPUnit 5.7.27 by Sebastian Bergmann and contributors.
    
    .F 2 / 2 (100%)
    
    Time: 275 ms, Memory: 4.25MB
    
    There was 1 failure:
    
    1) Test\PluginTest::testSum
    Failed asserting that 3 matches expected 4.
    
    E:\thxyh99\xampp\htdocs\project\zendframework\Test\PluginTest.php:37
    
    FAILURES!
    Tests: 2, Assertions: 4, Failures: 1.
