<?php
	class Client{
		public function script($js="Apayus", $module="apayus"){
			$url = Apayus::this()->url($module);
			return "<script src=\"{$url}js/{$js}.js\" type=\"text/javascript\"></script>";
		}
		public function link($css="Apayus", $module="apayus"){
			$url = Apayus::this()->url($module);
			return  "<link rel=\"stylesheet\" href=\"{$url}css/{$css}.css\">";
		}
		
		public function footer($dta){
			$path = Apayus::this()->route("main");
			return  Apayus::lib("notary")->compile(array(), $path."/client/html/footer.html");
		}
		
		public function header(){
			$path = Apayus::this()->route("main");
			$user = Apayus::lib("user")->current();
			if($user["role"] == "guest") {
				return '<header class="navbar navbar-fixed-top"> </header>';
			}else{
				$user["apayus"] = Apayus::this();
				$user["module"] = Apayus::lib("engine")->rout[1];
				$user['cfg'] = Apayus::lib("engine")->cfg["sys"];
				return  Apayus::lib("notary")->compile($user, $path."/server/tpl/header.php");
			}
		}
	}
