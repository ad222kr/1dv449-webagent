<?php

require_once("src/controllers/ScrapeController.php");
require_once("src/views/ScrapeView.php");
require_once("src/models/Scraper.php");
require_once("src/views/LayoutView.php");
require_once("src/views/FormView.php");

ini_set('display_errors','On');
ini_set('display_errors', 1);
error_reporting(E_ALL);


$lv = new \view\LayoutView();
$fv = new \view\FormView();
$ctrl = new \controller\ScrapeController($fv);

$ctrl->doControl();

$lv->render($ctrl->getView()->getResponse());




