<?php
require_once('index.php');
$database = DB::getInstance();
$database->get('users_data', array(), 'distance');
$results = $database->results();
echo $results = json_encode($results);