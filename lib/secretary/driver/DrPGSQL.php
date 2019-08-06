<?php
/* 
 *
 * @author: Apayus CS Team 
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for PostgresSQL DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
class DrPGSQL extends DbDriver
{
	public $user;   
	public $pass;    
	public $host; 
	public $port;  

	public function __construct($config)
	{
		$this->name = 'postgres';
		$this->user = 'postgres';   
		$this->pass = 'postgres';    
		$this->host = 'localhost'; 
		$this->port = '5432';  
		parent::__construct($config);
	}

	public function query($sql)
	{
		$this->conect();
		$out = pg_query($this->connection, $sql);
		$this->records[] = $sql;
		$out = $this->iselect($sql) ? $this->extract($out) : $out;
		$this->disconect();
		return $out;
	}

	public function conect()
	{
		$this->connection = pg_connect("host={$this->host} port={$this->port} dbname={$this->name} user={$this->user} password={$this->pass}")		
		or die("ERROR: No se pudo establecer la coneccion con el servidor");
	}

	public function disconect()
	{
		pg_close($this->connection)
		or die ("ERROR: No se pudo cerrar la coneccion");
	}

	public function extract($count){
		if(!$count) return false;
		$out = array();
		while( $value = pg_fetch_assoc($count) ) $out[] = count($value)>1 ? $value : array_pop($value);
		return $out;
	}
}
