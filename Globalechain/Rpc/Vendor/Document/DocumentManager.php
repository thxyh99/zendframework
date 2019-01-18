<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Vendor\Document;

use Globalechain\Rpc\Api\ApiProviderInterface;
use Globalechain\Rpc\ApplicationParameter;
use Globalechain\Rpc\RpcGetRequest;

/**
 * Description of DocumentManager
 *
 * @author xieyihong
 */
class DocumentManager
{

    //put your code here
    public function rpcDocument(RpcGetRequest $rpcGetRequest)
    {
        $server = $rpcGetRequest->getServer();
        $apiConfig = $rpcGetRequest->getApiConfig();

        $classArr = array();

        foreach ($apiConfig as $config) {

            $configClass = new ApplicationParameter();
            $configClass->initByConfig($config);
            if (empty($configClass->getType()) || !class_exists($configClass->getClass())) {
                continue;
            }

            $apiclass = $configClass->getClass();

            $api = new $apiclass($rpcGetRequest->getServiceLocator());

            if ($api instanceof ApiProviderInterface) {
                $server->setClass($api);
                array_push($classArr, $apiclass);
            }
        }

        $format = isset($_GET['format']) ? $_GET['format'] : 'html';
        $smd = $server->getServiceMap();
        $smd->setEnvelope($smd::ENV_JSONRPC_2);

        switch ($format) {
            case 'json':
                header('CONTENT-TYPE: application/json');
                echo $smd;
                break;
            case 'html':
                header("Content-type: text/html; charset=utf-8");
                $documenter = new Documenter();
                echo $documenter->generate($classArr);
                break;
            case 'markdown':
                header("Content-type: text/plain; charset=utf-8");
                $documenter = new Documenter();
                echo $documenter->generate($classArr, \FALSE);
                break;
        }
        return;
    }

}
