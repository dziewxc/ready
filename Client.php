<?php
$test = new Client;
$test->notAuthorized();
class Client
{
    const HOST = "http://localhost/ready/";
    public function getUsers()
    {
       $curl = curl_init();
       curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost/ready/users.php?apikey=YWRAYWR1LmFkdV9hZHVhZHU=',
        ));
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //response: { errors: [ { message: "You have to pass APIKEY" } ] }
    public function NoApiKey()
    {
       $curl = curl_init();
       curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost/ready/users.php?',
        ));
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //response: ﻿{"status":"success","apikey":"YWRAYWR1LmFkdV9hZHVhZHU=","data":{"email":"ad@adu.adu","haslo":"aduadu"}}
    public function login()
    {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, self::HOST . "login.php");
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS,
            "email=ad@adu.adu&haslo=aduadu");
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //response: ﻿{"errors":[{"message":"You passed wrong data"}]}
    public function notValidLogin()
    {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, self::HOST . "login.php");
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS,
            "email=ad@adu.adu&haslo=wrongpassword");
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //response: { status: "success", apikey: "bWFyaXVzekBrb3dhbHNraS5wbF8=", data: { email: "mariusz@kowalski.pl", password: "password", password_again: "password" } }
    public function register()
    {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, self::HOST . "register.php");
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS,
            "email=mariusz@kowalski.pl&password=password&password_again=password");
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //response:{ status: "success", data: { longitude: "4533.55", latitude: "34.23" } }
    public function changeData()
    {
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, self::HOST . "data.php?apikey=YWRAYWR1LmFkdV9hZHVhZHU=");
       curl_setopt($curl, CURLOPT_POST, 1);
       curl_setopt($curl, CURLOPT_POSTFIELDS,
            "longitude=4533.55&latitude=34.23");
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
    
    //Response: { errors: [ { message: "You passed wrong credentials" } ] }
    public function notAuthorized()
    {
       $curl = curl_init();
       curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost/ready/users.php?apikey=WRONGKEY',
        ));
       $result = curl_exec($curl);
       
       echo $result;
       curl_close($curl);
    }
}



