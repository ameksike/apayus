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
	class Apayus{
		protected $libs;
		protected $path;

		public function __construct($option=array()){
			$this->lib = array();
			$this->path = array(dirname(__FILE__)."/../../", dirname(__FILE__)."/../../../mod/");
			$this->setting($option);
		}

		public function config($path=""){
			return @include $path."/cfg/config.php";
		}

		public function setting($option=array()){
			$this->option = $option;
			if(isset($option['path'])) {
				if(is_string($option['path'])) $this->path[] = $option['path'];
				else if(is_array($option['path'])) $this->path = array_merge($this->path, $option['path']); 
			}
		}

		public function load($name){
			$class = ucfirst($name);
			$file  = $this->search("$name/$class.php");
			$file  = $file ? $file : $this->search("$name/server/$class.php");
			if($file) {
				include_once $file;
				return new $class;
			}else return false;
		}

		protected function search ($file){
			foreach($this->path as $i)
				if(file_exists($i.$file)) return $i.$file;
			return false;
		}

		public function route ($module){
			foreach($this->path as $i){
				if(is_dir($i.$module)) return $i.$module;
			}
				
			return false;
		}

		public function url($name=false){
			$base = $this->get("request")->url . "index.php";
		
			if($name){
				$path = $this->route($name);
				return !empty($path) ? $this->get("request")->getURL($path) : "$base/$name";
			}else return $base;
		}

		public function get($name){
			if(!isset($this->libs[$name])) $this->libs[$name] = $this->load($name); 
			return $this->libs[$name];
		}

		public function __get($name){
			return (isset($this->option[$name])) ? $this->option[$name] : $this->get(name);
		}

		static  $obj = 0;
		public static function this($path=array()){
			self::$obj = (!self::$obj) ? new self($path) : self::$obj;
			return self::$obj;
		}
		public static function lib($name){
			return self::this()->get($name);
		}
		public static function cfg($name){
			return self::this()->option[$name];
		}
	}
