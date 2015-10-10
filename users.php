<?php
require_once('index.php');
$errorList = new StdClass;
$errorList->errors = array();
$errorObj = new StdClass;
if(Input::exists('get')) {
    if(Input::get('apikey') === '')
    {
        $errorObj->message = "You have to pass ApiKey as GET parameter";
        $errorList->errors[] = $errorObj;
        echo json_encode($errorList);
        return true;
    }
    
    $key = base64_decode(Input::get('apikey'));
    $email = strstr($key, '_', true);
    
    $user = new User;
    if($user->find($email))
    {
        $database = DB::getInstance();
        $database->get('users_data', array(), 'distance');
        $results = $database->results();
        
        echo $results = json_encode($results);
    } else 
    {
        $errorObj->message = "You passed wrong credentials";
        $errorList->errors[] = $errorObj;
        echo json_encode($errorList);
    }
} else {
    $errorObj->message = "You have to pass APIKEY";
    $errorList->errors[] = $errorObj;
    echo json_encode($errorList);
}