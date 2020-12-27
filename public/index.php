<?php

use Kring\core;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . "/kring/Kring.php";

$kring = new \kring\core\Kring();
$kring->Run(9);
