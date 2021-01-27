<?php

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
 * Description of Model_ctrl
 *
 * @author sjnx
 */
use kring\database AS db;
use kring\utilities\comm;

class Model_ctrl {

    function __construct() {
        
    }

    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }

}
