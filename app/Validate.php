<?php
class Validate {
	private $status = false, 
			$errors = array(),
			$database = null;
			
	public function __construct() {
		if($this->database = DB::getInstance()) {
		};
	}
	
	public function check($source, $items = array()) {
           foreach($items as $item => $rules) {
                foreach($rules as $rule => $rule_value) {
                    $value = trim($source[$item]);
                    $item = escape($item);
                    
                    if($rule === 'required' && empty($value)) {
                        $this->addError("You have to fill {$item} area.");
                    } else if(!empty($value)) {
                        switch($rule) {
                            case 'min':
                                if(strlen($value) < $rule_value) {
                                    $this->addError("{$item} must have min {$rule_value} letters.");
                                }
                            break;
                            case 'max':
                                if(strlen($value) > $rule_value) {
                                    $this->addError("{$item} must have max {$rule_value} letters.");
                                }
                            break;
                            case 'matches':
                                if($value != $source[$rule_value]) {  
                                    $this->addError("{$rule_value} must be identical to {$item}.");
                                }
                            break;
                            case 'unique':
                                if($check = $this->database->get($rule_value, array($item, '=', $value))) { 
                                }
                                if($check->count()) { 
                                    $this->addError("{$item} already exists.");
                                }
                            break;
                            case 'mail':
                                if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    $this->addError("{$value} is not a valid e-mail adress.");
                                }
                            break;
                            case 'float':
                                if(!is_numeric($value)) {
                                    $this->addError("{$value} is not numeric.");
                                }
                            break;
                        }
                    }
                }
            }
		if(empty($this->errors)) {
			$this->status = true;
		}
	}

	private function addError($error) {
		$this->errors[] = $error; 
	}
	
	public function errors() {
		return $this->errors;
	}
	
	public function passed() {
		return $this->status;
	}
}



?>	