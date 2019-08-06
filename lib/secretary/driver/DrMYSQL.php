<?php
/* 
 *
 * @author: Apayus CS Team 
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for MySql DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
class DrMYSQL extends DbDriver
{
	public $user;   
	public $pass;    
	public $host; 
	public $port;  

	public function __construct($config)
	{
		$this->name = 'default';
		$this->user = 'root';   
		$this->pass = 'root';    
		$this->host = 'localhost'; 
		$this->port = '3306';  
		parent::__construct($config);
	}

	public function query($sql)
	{
		$this->conect();
		$out = mysql_query($sql);
		$this->records[] = $sql;
		$out = $this->iselect($sql) ? $this->extract($out) : $out;
		$this->disconect();
		return $out;
	}

	public function conect()
	{
		$this->connection = mysql_connect($this->host, $this->user, $this->pass)		
		or  die("ERROR: No se pudo establecer la coneccion con el servidor");		
		mysql_select_db($this->name, $this->connection)		
		or die ("ERROR: No se pudo establecer la coneccion con la BD");
	}

	public function disconect()
	{
		mysql_close($this->connection)
		or die ("ERROR: No se pudo cerrar la coneccion");
	}

	public function extract($count){
		if(!$count) return false;
		$out = array();
		while( $value = mysql_fetch_assoc($count) ) $out[] = count($value)>1 ? $value : array_pop($value);
		return $out;
	}
}
