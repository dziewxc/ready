<?php
require_once('index.php');
$database = DB::getInstance();

function distance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $distance = ($dist * 60 * 1.1515) * 1.609344;
    
    return $distance;
}

$errorList = new StdClass;
$errorList->errors = array();
$errorObj = new StdClass;

if(Input::exists('get')) {
    $key = base64_decode(Input::get('apikey'));
    if(Input::get('apikey') === '')
    {
        $errorObj->message = "You have to pass APIKEY";
        $errorList->errors[] = $errorObj;
        echo json_encode($errorList);
        return true;
    }
    $email = strstr($key, '_', true);

    $user = new User;
    $user->find($email);
    $userId = $user->data()->id;
    $distance = '';
    
    $lat2 = 50.0467;
    $lon2 = 20.004;
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'longitude' => array(
            'float' => true,
            'required' => true,
        ),
        'latitude' => array(
            'float' => true,
            'required' => true,
        )
    ));
    if($validate->passed()) {
        try
        {
            $distance =  distance(Input::get('latitude'), Input::get('longitude'), $lat2, $lon2);
        
            $data = array(
                'latitude' => Input::get('latitude'),
                'longitude' => Input::get('longitude'),
                'user_id' => $userId,
                'name' =>  Input::get('name'),
                'distance' => $distance,
                );
                //you can add only once for one person
            if(!$database->insert('users_data', $data)) {
                throw new Exception('We have problem with adding data');
            } else
            {
                $response = new StdClass;
                $response->status = "success";
                $postData = new StdClass;
                foreach($_POST as $postKey =>$postValue)
                {
                    $postData->$postKey = $postValue;
                }
                $response->data = $postData;
                echo json_encode($response);
            }
        } catch(Exception $e) {
                die($e->getMessage());
            }
    } else {
        foreach($validate->errors() as $error)
        {
            $errorObj = new StdClass;
            $errorObj->message = $error;
            $errorList->errors[] = $errorObj;
        }
        echo json_encode($errorList);
        return true;
        }
} else {
    $errorObj->message = "You have to pass APIKEY";
    $errorList->errors[] = $errorObj;
    echo json_encode($errorList);
}