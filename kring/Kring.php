<?php

namespace kring\core;

/*
 * Copyright (c) 2020, sjnx
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * The main Kring simple framework class file.
 *
 *
 * @author Sujan C.barty
 */
class Kring {

    public $controllerName;
    public $methodname;
    public $arguments;

    function __construct() {
        
    }

    function coreconf($varname) {
        require(dirname(__DIR__) . "/configs/core.php");
        if (isset($core[$varname])) {
            return $core[$varname];
        } else {
            return false;
        }
    }

    private function get_request() {
        return $_SERVER['REQUEST_URI'];
    }

    private function getrequestarr() {
        return explode("/", $this->get_request());
    }

    private function getClass() {
        if (isset($_GET['app'])) {
            $classname = ucfirst(strtolower($_GET['app'])) . "Controller";
        } elseif (isset($this->getrequestarr()[1]) && strlen($this->getrequestarr()[1]) > 3) {
            $classname = ucfirst(strtolower($this->getrequestarr()[1])) . "Controller";
        } else {
            $classname = $this->coreconf('defaultController');
        }
        require_once dirname(__DIR__) . '/src/controllers/' . $classname . ".php";
        return new $classname();
    }

    private function getMethod() {
        if (isset($_GET['opt'])) {
            $classname = strtolower($_GET['opt']);
        } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 2) {
            $classname = strtolower($this->getrequestarr()[2]);
        } else {
            $classname = $this->coreconf('defaultMethod');
        }
        return $classname;
    }

    private function getparams() {
        $totalobj = count($this->getrequestarr());
        $totalindx = $totalobj - 1;
        $ret = [];

        if ($totalobj > 3) {
            $t = 3;
            while ($t <= $totalindx) {
                $ret[$t] = $this->getrequestarr()[$t];
                $t++;
            }
        }
        return $ret;
    }

    public function get_version() {
        return "Version 1.0.0 (First Version)";
    }

    public function Run() {
        $method = $this->getMethod();
        echo $this->getClass()->$method($this->getparams()); // . "()";
        //print_r($this->getparams());
    }

}
