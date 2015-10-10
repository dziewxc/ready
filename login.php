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
        
        $apikey = base64_encode(Input::get('email') . "_" . Input::get('haslo'));
        if($login) {
            $response = new StdClass;
            $response->status = "success";
            $response->apikey = $apikey;
            $postData = new StdClass;
            foreach($_POST as $postKey =>$postValue)
            {
                $postData->$postKey = $postValue;
            }
            $response->data = $postData;
            echo json_encode($response);
        } else {
            $errorList = new StdClass;
            $errorList->errors = array();
            $errorObj = new StdClass;
            $errorObj->message = $error;
            $errorList->errors[] = $errorObj;
            echo json_encode($errorList);
        }
    } else {
        foreach($validate->errors() as $error)
        {
            $errorObj = new StdClass;
            $errorObj->message = $error;
            $errorList->errors[] = $errorObj;
        }
        echo json_encode($errorList);
    }
}
