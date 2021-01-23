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

    function getApp() {
        require(dirname(__DIR__) . "/configs/applications.php");
        $defappfolder = isset($app[$this->getrequestarr()[1]]) ? $app[$this->getrequestarr()[1]] : "apps";
        return $defappfolder;
    }

    function coreconf($varname) {
        require(dirname(__DIR__) . "/configs/core_" . $this->getApp() . ".php");
        if (isset($core[$varname])) {
            return $core[$varname];
        } else {
            return false;
        }
    }

    function dbconf($varname) {
        require(dirname(__DIR__) . "/configs/database.php");
        if (isset($db[$varname])) {
            return $db[$varname];
        } else {
            return false;
        }
    }

    function getV() {
        return $this->coreconf('defaultVersion');
    }

    function isloggedin() {
        return true;
    }

    private function get_request() {
        return $_SERVER['REQUEST_URI'];
    }

    private function getrequestarr() {
        return explode("/", $this->get_request());
    }

    public function getClassName() {
        if ($this->getApp() == "apps") {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[1]) && strlen($this->getrequestarr()[1]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[1]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        } else {
            if (isset($_GET['app'])) {
                $classname = ucfirst(strtolower($_GET['app']));
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = ucfirst(strtolower($this->getrequestarr()[2]));
            } else {
                $classname = $this->coreconf('defaultController');
            }
        }
        //echo $classname;
        //exit();
        return $classname;
    }

    private function getClass() {
        $classname = $this->getClassName();
        if ($classname == "Css" || $classname == "Js" || $classname == "Asset") {
            require_once dirname(__DIR__) . '/kring/asset.php';
            return new assets();
        } else {

            if (is_file(dirname(__DIR__) . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php")) {
                require_once dirname(__DIR__) . '/' . $this->getApp() . '/' . $this->getV() . '/controllers/' . $classname . ".php";
                return new $classname();
            } else {
                require_once 'error.php';
                return new \errorhndlr();
            }
        }
    }

    public function getMethod() {
        if ($this->getApp() == "apps") {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[2]) && strlen($this->getrequestarr()[2]) > 1) {
                $classname = strtolower($this->getrequestarr()[2]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        } else {
            if (isset($_GET['opt'])) {
                $classname = strtolower($_GET['opt']);
            } elseif (isset($this->getrequestarr()[3]) && strlen($this->getrequestarr()[3]) > 1) {
                $classname = strtolower($this->getrequestarr()[3]);
            } else {
                $classname = $this->coreconf('defaultMethod');
            }
        }
        return $classname;
    }

    public function getparams() {
        $totalobj = count($this->getrequestarr());
        $totalindx = $totalobj - 1;
        $ret = [];

        if ($totalobj > 1) {
            $t = 2;
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
        //echo "Get App:" . $this->getApp();
        $method = $this->getMethod();
        if (method_exists($this->getClass(), $method)) {
            if ($this->getClass()->adminarea == 1 && !$this->isloggedin()) {
                
            } else {
                $this->getClass()->$method($this->getparams());
            }
            // . "()";
        } elseif ($this->getClassName() == "Css") {
            $this->getClass()->css($this->getparams());
        } elseif ($this->getClassName() == "Js") {
            $this->getClass()->jscript($this->getparams());
        } elseif ($this->getClassName() == "Asset") {
            $this->getClass()->asset();
        } else {
            require_once 'error.php';
            $err = new \errorhndlr();
            echo $err->index([]);
        }

        //print_r($this->getparams());
    }

}
