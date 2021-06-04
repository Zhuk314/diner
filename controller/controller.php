<?php

class Controller
{
    private $_f3; //router

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function home()
    {
        //Display the home page
        $view = new Template();
        echo $view->render('views/home.html');
    }

    function order1()
    {
        //Reinitialize session array
        $_SESSION = array();

        //Instantiate an Order object
        //$order = new Order();
        //$_SESSION['order'] = $order;

        $_SESSION['order'] = new Order();
        //var_dump($_SESSION['order']);

        //Initialize variables to store user input
        $userFood = "";
        $mealId = 0;

        //If the form has been submitted, add the data to session
        //and send the user to the next order form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //var_dump($_POST);

            $userFood = $_POST['food'];
            $mealId = $_POST['meal'];

            //If food is valid, store data
            if(Validation::validFood($userFood)) {
                $_SESSION['order']->setFood($userFood);
            }
            //Otherwise, set an error variable in the hive
            else {
                $this->_f3->set('errors["food"]', 'Please enter a food');
            }

            //If meal is valid, store data
            if(!empty($mealId) && Validation::validMeal($mealId)) {
                $_SESSION['order']->setMeal($mealId);
            }
            //Otherwise, set an error variable in the hive
            else {
                $this->_f3->set('errors["meal"]', 'Invalid meal selected');
            }

            //If there are no errors, redirect to order2 route
            if (empty($this->_f3->get('errors'))) {
                header('location: order2');
            }
        }

        //Get the data from the model
        $meals = $GLOBALS['dataLayer']->getMeals();
        $this->_f3->set('meals', $meals);

        //Store the user input in the hive
        $this->_f3->set('userFood', $userFood);
        $this->_f3->set('userMeal', $mealId);

        //Display the first order form
        $view = new Template();
        echo $view->render('views/orderForm1.html');
    }

    function order2()
    {
        //Initialize variables for user input
        $userConds = array();

        //If the form has been submitted, validate the data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //var_dump($_POST);

            //If condiments are selected
            if (!empty($_POST['conds'])) {

                //Get user input
                $userConds = $_POST['conds'];

                //If condiments are valid
                if (Validation::validCondiments($userConds)) {
                    $_SESSION['order']->setCondiments(implode(", ", $userConds));
                }
                else {
                    $this->_f3->set('errors["conds"]', 'Invalid selection');
                }
            }

            //If the error array is empty, redirect to summary page
            if (empty($this->_f3->get('errors'))) {
                header('location: summary');
            }
        }

        //var_dump($userConds);

        //Get the condiments from the Model and send them to the View
        $this->_f3->set('condiments', DataLayer::getCondiments());

        //Add the user data to the hive
        $this->_f3->set('userConds', $userConds);

        //Display the second order form
        $view = new Template();
        echo $view->render('views/orderForm2.html');
    }

    function summary()
    {
        //Save the order to the database
        //global $dataLayer;
        //$dataLayer->saveOrder();
        //--or--
        $orderId = $GLOBALS['dataLayer']->saveOrder($_SESSION['order']);
        $this->_f3->set('orderId', $orderId);

        //Display the second order form
        $view = new Template();
        echo $view->render('views/summary.html');

        //This might be problematic
        unset($_SESSION['order']);
    }

    function admin()
    {
        //Get the data from the model
        $orders = $GLOBALS['dataLayer']->getOrders();
        $this->_f3->set('orders', $orders);

        //Display the admin page
        $view = new Template();
        echo $view->render('views/admin.html');
    }
}