<?php
/* 
 *
 * @author: Apayus CS Team 
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011 
 * @update: 23/4/2011 
 * @description: This is simple and Light Driver for DBSM 
 * @require: PHP >= 5.2.*, libphp5-mysql 
 * 
 */
abstract class DbDriver
{
	public  $name;
	protected $records;
	protected $connection;

	public function __construct($config=false)
	{
		$this->setting($config);
		$this->records = array();
	}

	public function setting ($key=false, $value=false){
		if($key){
			if(is_array($key)) 
				foreach($key as $k=>$i) $this->{$k} = $i;
			else if($key) $this->{$key} = $value;
		}else return $this->config();
	}

	protected function iselect($sql){
		return (strtolower(substr(trim($sql), 0, 6)) == 'select');
	}
	protected function config(){ return '';}

	public function trace(){ return $this->records; }

	abstract public function query($sql);
	abstract public function conect();
	abstract public function disconect();
	abstract public function extract($count);
}
