<?php

//Turn on error-reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require necessary files
require_once ('vendor/autoload.php');

//Start a session AFTER the autoload***
session_start();

//Instantiate classes
$f3 = Base::instance();
$con = new Controller($f3);
$dataLayer = new DataLayer();

//Test code
//$result = $dataLayer->getMeals();
//var_dump($result);
//$dataLayer->saveOrder(new Order('taco', 'lunch', 'salsa'));
/*
echo "<pre>";
$result = $dataLayer->getOrders();
var_dump($result);
echo "</pre>";
*/

//Define default route
$f3->route('GET /', function(){

    $GLOBALS['con']->home();
});

$f3->route('GET /breakfast', function(){

    //Display the breakfast page
    $view = new Template();
    echo $view->render('views/breakfast.html');
});

$f3->route('GET /breakfast/brunch/mothers-day', function(){

    //Display the breakfast page
    $view = new Template();
    echo $view->render('views/mothers-day-brunch.html');
});

$f3->route('GET|POST /order1', function(){
    $GLOBALS['con']->order1();
});

$f3->route('GET|POST /order2', function($f3){
    $GLOBALS['con']->order2();
});

$f3->route('GET /summary', function(){
    $GLOBALS['con']->summary();
});

$f3->route('GET /admin', function(){
    $GLOBALS['con']->admin();
});

//Run Fat-Free
$f3->run();
