<?php
/*
 * @author: Apayus CS Team
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011
 * @update: 23/4/2011
 * @description: This is simple and Light loader libs 
 * @require: PHP >= 5.2.*
 *
 * */
	class Request
	{	
		public $all;
		public $url;
		public function __construct(){
			$this->all = array();
			$this->url = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
			foreach($_REQUEST as $k=>$i) $this->all[$k] = json_encode($i, true);
		}
		public function getURL($path){
			$path = realpath($path);
			$urli = str_ireplace(realpath(dirname($_SERVER['SCRIPT_FILENAME'])).DIRECTORY_SEPARATOR, "", $path );
			$urli = str_replace("\\", "/", $urli);
			return $this->url . $urli.'/client/';
		}  
		public function pretty(){
			return isset($_SERVER["PATH_INFO"]) ? explode("/", $_SERVER["PATH_INFO"]) : array();
		} 
	}
