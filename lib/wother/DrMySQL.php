<?php
/* 
 *
 * @author: Apayus CS Team 
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for MySql DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
class DrMySQL
{
	public  $user;   
	public  $pass;    
	public  $host;  
	public  $name; 
	public  $port; 
	protected $connection; 
	protected $records;

	public function __construct($name, $user='root', $pass='root', $host='localhost', $port='5432')
	{
		$this->setting($name, $user, $pass, $host, $port);
		$this->records = [];
	}

	public function setting ($name, $user='root', $pass='root', $host='localhost', $port='5432'){
		if(is_array($name)) 
			foreach($name as $k=>$i) $this->{$k} = $i;
		else {
			$this->host = $host;
			$this->port = $port;
			$this->name = $name;
			$this->user = $user;
			$this->pass = $pass;
		}
	}

	public function query($sql)
	{
		$this->start();
		$out = mysql_query($sql);
		$this->records[] = $sql;
		$out = $this->iselect($sql) ? $this->extract($out) : $out;
		$this->close();
		return $out;
	}

	protected function start()
	{
		$this->connection = mysql_connect($this->host, $this->user, $this->pass)		
		or  die("ERROR: No se pudo establecer la coneccion con el servidor");		
		mysql_select_db($this->name, $this->connection)		
		or die ("ERROR: No se pudo establecer la coneccion con la BD");
	}

	protected function close()
	{
		mysql_close($this->connection)
		or die ("ERROR: No se pudo cerrar la coneccion");
	}

	protected function extract($count){
		if(!$count) return false;
		$out = array();
		while( $value = mysql_fetch_assoc($count) ) $out[] = count($value)>1 ? $value : array_pop($value);
		return $out;
	}

	public function trace(){
		return $this->records;
	}

	static  $obj = 0;
	public static function this($name='default', $user='root', $pass='root', $host='localhost', $port='5432'){
		self::$obj = (!self::$obj) ? new self($name, $user, $pass, $host, $port) : self::$obj;
		return self::$obj;
	}
}
