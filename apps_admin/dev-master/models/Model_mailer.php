<?php
/*

*/
use kring\database AS db;
use kring\utilities\comm;
class Model_mailer{

    function __construct() {


    }
    function comm() {
        return new comm();
    }

    function dbal() {
        return new db\dbal();
    }


}