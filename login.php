<?php
require_once('index.php');
if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'email' => array('required' => true),
        'haslo' => array('required' => true),
    ));
    
    if($validate->passed()) {
        $user = new User();
        $login = $user->login(Input::get('email'), Input::get('haslo'));
        
        if($login) {
            $response = new StdClass;
            $response->status = "success";
            $postData = new StdClass;
            foreach($_POST as $postKey =>$postValue)
            {
                $postData->$postKey = $postValue;
            }
            $response->data = $postData;
            echo json_encode($response);
        } else {
            echo "You passed wrong data";
        }
    } else {
        $errorList = new StdClass;
        $errorList->errors = array();
        foreach($validate->errors() as $error)
        {
            $errorObj = new StdClass;
            $errorObj->message = $error;
            $errorList->errors[] = $errorObj;
        }
        echo json_encode($errorList);
    }
}
