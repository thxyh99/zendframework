<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Globalechain\Rpc\Vendor\Document;

use Michelf\Markdown;
use ReflectionClass;
use ReflectionMethod;

/**
 * Description of Document
 *
 * @author xieyihong
 */
class Documenter
{

    //put your code here
    protected function genLine($text = \NULL)
    {
        if ($text) {
            echo $text;
        }
        echo PHP_EOL;
    }

    public function generate($classnames, $convertToHtml = \TRUE)
    {
        ob_start();
        $this->genLine('# JsonRPC Protocol');
        $this->genLine();
        $this->genLine('@METHODHTML@');
        $this->getLine('## Method Details');
        $this->genLine();
        $this->genLine('## Class Name');

        $methodnames = array();

        foreach ($classnames as $classname) {
            $this->genLine($classname);
            $class = new ReflectionClass($classname);
            foreach ($class->getMethods() as $method) {
                $methodname = $method->getName();
                if (in_array($methodname, array('__construct'))) {
                    continue;
                }
                $methodObj = new ReflectionMethod($class->getName(), $methodname);
                if (!$methodObj->isPublic()) {
                    continue;
                }

                array_push($methodnames, $methodname . '|' . $classname);
                $this->genLine();

                $this->genLine('### Method Name:');
                $this->genLine('### <a name=' . $methodname . ' id=' . $methodname . '>' . $methodname . '(Instance Of Class ' . $classname . ')</a>' . '<a href=#methodlist>TOP</a>');
                $this->genLine('### Method Detail： ');
                $docComment = $method->getDocComment();
                $this->genLine('### ' . $docComment == '' ? 'No comment' : preg_replace('/\r\n/', '</br>', $docComment));
                $this->genLine('### Params： ');
                $this->genLine();
                foreach ($method->getParameters() as $param) {
                    $this->genLine($this->getParams($param->name, $docComment));
                    $this->genLine();
                }
                $this->genLine();
                $this->genLine('### Return： ');
                $this->genLine($this->getReturn($docComment));
                $this->genLine();
                $this->genLine('### Exception Type： ');
                $this->genLine($this->getThrows($docComment));
                $this->genLine();
                $this->genLine('### Call Sample： ');
                $this->genLine($this->getExample($docComment));
                $this->genLine();
            }
        }
        sort($methodnames);
        $html = "<div>";
        foreach ($methodnames as $item) {
            $tmpArr = explode('|', $item);
            $methodname = $tmpArr[0];
            $classname = $tmpArr[1];
            $html .= "<div style='float:left;margin:10px 10px'><a  href=#{$methodname} title='Instance Of Class {$classname}'>{$methodname}</a></div>";
        }
        $html .= "</div>";

        $doc = ob_get_clean();
        $doc = str_replace('@METHODHTML@', $html, $doc);

        return $convertToHtml ? Markdown::defaultTransform($doc) : $doc;
    }

    private function getParams($name, $doc)
    {
        $arrayDoc = $this->explodDoc($doc);
        $retval = '';
        foreach ($arrayDoc as $v) {
            $matches = array();
            preg_match("/param.+/", $v, $matches);
            foreach ($matches as $item) {
                if (strpos($item, $name) !== false) {
                    $retval = trim($item, 'param');
                }
            }
        }
        if ($retval == '') {
            $retval = '$' . $name . '--' . $this->getWarning('This parameter can\'t find the corresponding comment in the comment, can\'t determine its type, please add its comment');
        }
        return $retval;
    }

    private function getReturn($doc)
    {
        $array_doc = $this->explodDoc($doc);
        $retval = '';
        foreach ($array_doc as $v) {
            $matches = array();
            preg_match("/return.+/", $v, $matches);
            foreach ($matches as $item) {
                $retval = trim($item, 'return');
                break;
            }
        }
        if ($retval == '') {
            $retval = $this->getWarning('The method cannot find the corresponding return return value annotation');
        }
        return $retval;
    }

    private function getThrows($doc)
    {
        $array_doc = $this->explodDoc($doc);
        $retval = '';
        foreach ($array_doc as $v) {
            $matches = array();
            preg_match("/throws.+/", $v, $matches);
            foreach ($matches as $item) {
                $retval .= trim($item, 'throws') . '</br>';
            }
        }
        if ($retval == '') {
            $retval = $this->getWarning('No exception type annotation');
        }
        return $retval;
    }

    private function getExample($doc)
    {
        $array_doc = $this->explodDoc($doc);
        $retval = '';
        foreach ($array_doc as $v) {
            $matches = array();
            preg_match("/example.+/", $v, $matches);
            foreach ($matches as $item) {
                $retval = ltrim($item, 'example');
                $retval = trim($retval);
            }
        }
        if ($retval == '') {
            $retval = $this->getWarning('Temporarily no instance is called');
        } else {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/api_example/" . $retval;
            if (file_exists($path)) {
                $retval = file_get_contents($path);
            }
        }
        return $retval;
    }

    private function explodDoc($doc)
    {
        return explode("\r\n", $doc);
    }

    private function getWarning($msg)
    {
        return "<font color=red>WARNING:" . $msg . "</font>";
    }

}
