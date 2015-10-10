<?php
//model
class User {
	private $database;
	private $data;
	private $isLoggedIn;
	
	public function __construct($user = null) {
		$this->database = DB::getInstance();
		$this->find($user);
	}
    
	public function login($login = null, $haslo = null) {
        $user = $this->find($login);
		if($user) {
			if($this->data()->haslo === Hash::make($haslo)) {
				return true;
			}
		}
	return false;
	}
    
	public function create($fields = array()) {
		if(!$this->database->insert('users', $fields)) {
			throw new Exception('Mamy pewien problem z utworzeniem Twojego konta!');
		}
	}
	
	public function find($user = null) {
		if($user) {
			$field = 'email';
			$data = $this->database->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->data = $data->first(); 
				return true;
			}
		}
		return false;
	}
	
	public function exists() {
		return(!empty($this->data)) ? true : false;
	}
	
	public function data() {
		return $this->data;
	}
}