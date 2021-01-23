<?php

session_start();
error_reporting(E_ALL);
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . "/kring/Kring.php";
$kring = new \kring\core\Kring();
$kring->Run();

