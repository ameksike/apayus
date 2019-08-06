<?php
/*
 * @author: Apayus CS Team
 * @mail: apayus.cs@gmail.com
 * @made: 23/4/2011
 * @update: 23/4/2011
 * @description: This is simple and Light Template Engine
 * @require: PHP >= 5.2.*
 *
 * */
class Notary
{
	protected $path = "";
	public function __construct($path = ""){
		$this->path = $path;
	}

	public function path($path=false){
		if(is_string($path)) $this->path = $path;
		return $this->path;
	}
	public function resolve($tplname=""){
		$tplfile = (file_exists($tplname)) ? $tplname : $this->path.$tplname;
		return pathinfo($tplfile);
	}
	public function compile($data='', $tplname=""){
		$tplinfo = $this->resolve($tplname);
		$tplfile = is_array($tplinfo) ? $tplinfo["dirname"] . DIRECTORY_SEPARATOR . $tplinfo["basename"]  : $tplname;
		$tpldvr  = is_array($tplinfo) ? $tplinfo["extension"] : "data";
		return $this->process($data, $tplfile,  $tpldvr);	
	}
	public function process($data, $tpl, $driver="html"){
		$data = (!is_array($data)) ? array("data"=>$data) : $data;
		extract($data);
		ob_start();
			switch($driver){
				case "php": include $tpl; break;
				case "html": $tpl = file_get_contents( $tpl);
				default: 
					$tpl = str_replace('"', '\"', $tpl);
					eval(' ?><?php echo "' . $tpl . '";?><?php ');
				break;
			}
		return ob_get_clean();
	}

	static  $obj = 0;
	public static function this($name='default', $user='root', $pass='root', $host='localhost', $port='5432'){
		self::$obj = (!self::$obj) ? new self($name, $user, $pass, $host, $port) : self::$obj;
		return self::$obj;
	}
}
