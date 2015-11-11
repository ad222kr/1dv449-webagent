<?php

require_once("scrapedemo.php");

ini_set('display_errors','On');
ini_set('display_errors', 1);
error_reporting(E_ALL);


$sd = new scrapedemo();
$sd->run();



