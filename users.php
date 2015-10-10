<?php
require_once('index.php');
if(Input::exists('get')) {
    if(Input::get('apikey') === '')
    {
        $errorList = new StdClass;
        $errorList->errors = array();
        $errorObj = new StdClass;
        $errorObj->message = "You have to pass ApiKey as GET parameter";
        $errorList->errors[] = $errorObj;
        echo json_encode($errorList);
        return true;
    }
    $database = DB::getInstance();
    $database->get('users_data', array(), 'distance');
    $results = $database->results();
    echo $results = json_encode($results);
}