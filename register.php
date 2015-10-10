<?php
require_once('index.php');
$user = new User();
if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'email' => array(
            'required' => true,
            'min' => 2,
            'max' => 20,
            'unique' => 'users',
            'mail' => true,
        ),
        'password' => array(
            'required' => true,
            'min' => 6,
        ),
        'password_again' =>array(
            'required' => true,
            'matches' => 'password',
        )
    ));

    
    if($validate->passed()) {
            try {
            $user->create(array(
                'haslo' => Hash::make(Input::get('password')),
                'email' => Input::get('email'),
            ));
            
            $response = new StdClass;
            $response->status = "success";
            $postData = new StdClass;
            foreach($_POST as $postKey =>$postValue)
            {
                $postData->$postKey = $postValue;
            }
            $response->data = $postData;
            echo json_encode($response);
                
            } catch(Exception $e) {
                die($e->getMessage());
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
        return true;
    }
}

