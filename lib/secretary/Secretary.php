<?php
/* 
 *
 * @author: Apayus CS Team 
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for manage DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
class Secretary
{
	protected $drivers;
	protected $active;

	public function __construct($driver='mysql'){
		$this->drivers = array();
		$this->setting($driver);
	}

	protected function driver($driver=false, $config=false){
		$driver = $driver ? $driver : $this->active;
		if(!isset($this->drivers[$driver])) $this->load($driver, $config);
		return $this->drivers[$driver];
	}

	public function load($driver='mysql', $config=false){
		$class = "Dr".strtoupper($driver);
		$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'driver'.DIRECTORY_SEPARATOR;
		if(!file_exists($path.$class.'.php')) return false;
		include_once $path."DbDriver.php";
		include_once $path.$class.'.php';
		$this->drivers[$driver] = new $class($config);
	}
	//...
	public function setting ($key=false, $value='$'){ 
		if($key){
			if(is_string($key)){
				if($key=='driver') $this->active = $value;
				else if($value=='$') $this->active = $key;
			}else $this->active = isset($key['driver']) ? $key['driver'] : $this->active;
		} 
		return $this->driver()->setting($key, $value);  
	}
	public function query($sql){ return $this->driver()->query($sql);  }
	protected function conect(){ $this->driver()->conect();  }
	protected function close(){ $this->driver()->close();  }
	protected function extract($count){ $this->driver()->extract($count);  }
	public function trace(){ $this->driver()->trace();  }
	//...
	public function __get($key)
	{
		if(isset($this->{$key})) return $this->{$key};
		else return $this->get()->{$key};
	}

	public function __set($key, $value)
	{
		if(isset($this->{$key})) return $this->{$key} = $value;
		else return $this->get()->{$key} = $value;
	}
	//...
	static  $obj = 0;
	public static function this($driver=false){
		self::$obj = (!self::$obj) ? new self($driver) : self::$obj;
		self::$obj->setting($driver);
		return self::$obj;
	}
}
