<?php

use kring\core\Controller;

/*
 * Copyright 2021 sjnx.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Description of Ctrl
 *
 * @author sjnx
 */
class Ctrl extends Controller {

    public $adminarea;

    function __construct() {
        parent::__construct();
        $this->adminarea = 1;
    }

    function model() {
        return $this->loadmodel('home');
    }

    function index($pr) {
        if (isset($_REQUEST['fd'])) {
            $data['title'] = "Ctrl Controller";
//$this->tg('home/dashboard.html', $data);
        } else {
            $data['title'] = "Admin Dashboard";
            $data['var'] = "Variable";
            $this->tg('home/dashboard.html', $data);
        }
    }

    function switchtheme($pr) {
        $_SESSION['theme'] = isset($pr[4]) && $pr[4] == 'color' ? $pr[4] : "colorl";
    }

}
