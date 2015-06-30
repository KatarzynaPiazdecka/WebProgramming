<?php
class Database
{
    private static $_instance;
    private $_query,
            $_pdo,
            $_results,
            $_error = false,
            $_count = 0;
    
    private function __construct()
    {
        try 
        {
	//$con=pg_connect("host=sbazy user=s175371 dbname=s175371 password=9KMgKf6t"); 	
	//('pgsql:host=localhost;dbname=DBNAME', 'USERNAME', 'PASSWORD');
	
            $this->_pdo = new PDO('pgsql:host=sbazy; dbname=s175371', 's175371', '9KMgKf6t');
		//('mysql:host=localhost; dbname=dogs', 'root', 'piaskowa123',
                //array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e) 
        {
            die($e->getMessage());
        }
    }
    
    public function getInstance()
    {
        if(!isset(self::$_instance))
        {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }
    
    public function insert($table, $data = array()) {
			$keys = array_keys($data);
			$values = '';
			$x = 1;
			
			foreach ($keys as $key) {
				$values .= '?';
				if($x < count($keys)) {
					$values .= ', ';      // --.' dokładanie do stringu
				}
				$x++;
			}			
			$sql = "INSERT INTO {$table} (" . implode(',',$keys) . ") VALUES ({$values})";
			if(!$this->query($sql, $data)->error()) {
				return true;
			}
		return false;
	}
    
    private function query($sql, $params = array())
    {
        if($this->_query = $this->_pdo->prepare($sql))
        {
            $x = 1;
            if(count($params))
            {
                foreach($params as $param)
                {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->_query->execute()) {
                
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
            return $this;
        }
    }
    
    private function action($action, $table, $where = array())
    {
        if(count($where) === 3)
        {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            
            $operators = array('=', '<>', '<', '>', '<=', '>=');
            if(in_array($operator, $operators))
            {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error())
                {
                    return $this;
                }
            }
        } else
        {
            if(!$this->query("{$action} FROM {$table}")->error())
            {
             return $this;       
            }
        }
        return false;
    }
    
    public function get($item, $table, $where = array())
    {
        $this->action("SELECT {$item}", $table, $where);
    }
    
    public function getDistinct($item, $table, $where = array())
    {
        $this->action("SELECT DISTINCT {$item}", $table, $where);
    }
    
    public function error() {
		return $this->_error;
	}
    public function results() {
		return $this->_results;
	}	
}