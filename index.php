<?php
ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);
require_once('conf.php');
require_once('sanitize.php');

spl_autoload_register(function($class) {
		require_once 'app/' . $class.'.php';
	}
);