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
	class Engine
	{
		public $path;
		public $dir;
		
		public function setting($dir="../../"){
			$this->dir = $dir;
			$this->cfg = Apayus::this()->config($this->dir);
			Apayus::this()->setting($this->cfg);
			set_error_handler("Engine::errorHandler");
		}
		
		static public function errorHandler($errno, $errstr, $errfile, $errline){
			return true;
		}
		public function listen(){
			$this->rout = Apayus::lib("request")->pretty();
			switch(count($this->rout)){
				case 0: 
				case 1: $this->rout[1] = 'main';  $this->rout[2] = 'index'; break;
				case 2: $this->rout[2] = 'index'; break; 
			}
			if(isset($this->cfg['security'])){
				session_start();
				$manager = $this->cfg['security']["manager"];
				Apayus::lib($manager)->config = $this->cfg; 
				if(!Apayus::lib($manager)->authorize($this->rout[1], $this->rout[2])) 
					Apayus::lib($manager)->denied($this->rout[1], $this->rout[2]);
			}
			$this->path = Apayus::this()->route($this->rout[1]);
			Apayus::lib($this->rout[1])->config = $this->cfg; 
			
			$out = (method_exists(Apayus::lib($this->rout[1]), $this->rout[2])) ? Apayus::lib($this->rout[1])->{$this->rout[2]}($_REQUEST) : false;
			
			if(property_exists( Apayus::lib($this->rout[1]), 'view')){
				$view = explode (":",Apayus::lib($this->rout[1])->view);
				if(count($view) > 1){
					$this->rout[1] = $view[0];
					$this->rout[2] = $view[1];
				}else $this->rout[2] = $view[0];
			}
			return $this->respond($out);
		}
		public function respond($data=false){
			$dta = is_array($data) ? $data : array("data"=>$data);
			$this->path = Apayus::this()->route($this->rout[1]);
			$dta['baseurl'] = Apayus::lib("request")->getURL($this->path);
			$dta['apayus'] = Apayus::this();
			$dta['module'] = $this->rout[1];
			$dta['cfg'] = $this->cfg["sys"];
			$path = $this->searchTPL();
			if($path){
				Apayus::lib("notary")->path($path);
				$tpl = Apayus::lib("notary")->compile($dta);
			}else $tpl = false;
			return ($tpl) ? $tpl : (is_string($data) ? $data : json_encode($data));
		}
		public function searchTPL(){
		    if(file_exists($this->path.$this->rout[2])) return $this->path.$this->rout[2];
		    else {
		        foreach($this->cfg['tpl'] as $k=>$i){
		            $path = $this->path.$i.$this->rout[2].".$k";
					//echo "$path <br>";
		            if(file_exists($path)) return $path;
		        }
		    }
		    return false;
		}
	}
