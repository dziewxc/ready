<?php
class DB 
{
	private static $_instance;
	private $database,
			$query,
			$error = false,
			$results,
			$count = 0;
				
	private function __construct() 
    {
		try {
			$this->database = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOException $e) {
		die($e->getMessage);
		}
	}
	
	public static function getInstance() 
    {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	public function query($sql, $params = array())
    {
		$this->error = false;
		if($this->query = $this->database->prepare($sql)) {
			$x = 1;
			if(count($params))  {
				foreach($params as $param)  {
					$this->query->bindValue($x, $param);
					$x++;
				}
			}
			if($this->query->execute()) {
				$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
				$this->count = $this->query->rowCount();
			} else {
				$this->error = true;
			}
		return $this;
        }
	}
	
	public function insert($table, $data = array()) 
    {
			$keys = array_keys($data);
			$values = '';
			$x = 1;
			
			foreach ($keys as $key) {
				$values .= '?';
				if($x < count($keys)) {
					$values .= ', ';
				}
				$x++;
			}			
			$sql = "INSERT INTO {$table} (`" . implode('`, `',$keys) . "`) VALUES ({$values})";
			if(!$this->query($sql, $data)->error()) {
				return true;
			}
		return false;
	}
	
	public function update($table, $id, $fields) 
    {
		$set = '';
		$x = 1;
		
		foreach($fields as $name =>$value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
			$set .= ', '; 
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if(!$this->query($sql, $fields)->error()) {
		return true;
		}
		return false;
		echo $this->query($sql, $fields)->error();
	}
	
	
	public function action($action, $table, $where = array(), $order = null) 
    {
        if($order) {
            $ordersql = "ORDER BY {$order} ASC";
        } else {
            $ordersql = '';
        }
		if(count($where) === 3) {
			$operators = array('=', '<', '>', '<=', '>=');
				
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			
					if(in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE {$field}{$operator} ? {$ordersql}";
					if(!$this->query($sql, array($value))->error()) {
                        
						return $this;
					}
				}	

		}	else {
			$sql = "{$action} FROM {$table} {$ordersql}";
			if($this->query($sql)) {
				return $this->results;
			}
		}
		return false;
	}
	
	public function get($table, $where = array(), $order = null)
    {
		return $this->action('SELECT *', $table, $where, $order);
	}
	
	public function error() 
    {
		return $this->error;
	}
	
	public function first() 
    {
		return $this->results[0];
	}
	
	public function count() 
    {
		return $this->count;
	}	
	
	public function results() 
    {
		return $this->results;
	}	
}





