<?php

//This is my controller for the diner project

//Turn on error-reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require necessary files
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');
require_once ('model/validation.php');

//Instantiate Fat-Free
$f3 = Base::instance();

//Define default route
$f3->route('GET /', function(){

    //Display the home page
    $view = new Template();
    echo $view->render('views/home.html');
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

$f3->route('GET|POST /order1', function($f3){

    //Reinitialize session array
    $_SESSION = array();

    //If the form has been submitted, add the data to session
    //and send the user to the next order form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //var_dump($_POST);

        //If food is valid, store data
        if(validFood($_POST['food'])) {
            $_SESSION['food'] = $_POST['food'];
        }
        //Otherwise, set an error variable in the hive
        else {
            $f3->set('errors["food"]', 'Please enter a food');
        }

        //If meal is valid, store data
        if(isset($_POST['meal']) && validMeal($_POST['meal'])) {
            $_SESSION['meal'] = $_POST['meal'];
        }
        //Otherwise, set an error variable in the hive
        else {
            $f3->set('errors["meal"]', 'Invalid meal selected');
        }

        //If there are no errors, redirect to order2 route
        if (empty($f3->get('errors'))) {
            header('location: order2');
        }
    }

    //Get the data from the model
    $f3->set('meals', getMeals());

    //Display the first order form
    $view = new Template();
    echo $view->render('views/orderForm1.html');
});

$f3->route('GET|POST /order2', function($f3){

    //If the form has been submitted, validate the data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //var_dump($_POST);

        //If condiments are selected
        if (!empty($_POST['conds'])) {

            //If condiments are valid
            if (validCondiments($_POST['conds'])) {
                $_SESSION['conds'] = implode(", ", $_POST['conds']);
            }
            else {
                $f3->set('errors["conds"]', 'Invalid selection');
            }
        }

        //If the error array is empty, redirect to summary page
        if (empty($f3->get('errors'))) {
            header('location: summary');
        }
    }

    //Get the condiments from the Model and send them to the View
    $f3->set('condiments', getCondiments());

    //Display the second order form
    $view = new Template();
    echo $view->render('views/orderForm2.html');
});

$f3->route('GET /summary', function(){

    //Display the second order form
    $view = new Template();
    echo $view->render('views/summary.html');
});

//Run Fat-Free
$f3->run();
