<?php
	class User{
		public function __construct(){
			$this->db = dirname(__FILE__)."/db/user.json";
		}
		
		public function edit(){
			$user = $this->current();
			return array("username" => $user["name"]);
		}
		
		public function logout(){
			$this->delSession();
			$this->header('main/index');
		}

		public function load(){
		//print_r($this->current());
			return $this->current();
		}

        public function header($url){
			$url = Apayus::this()->url($url);
			header("Location: $url");
		}

        public function dologin($request){
			$users = json_decode($this->get(), true);
			if(!isset($request["user"])) return "failed";
			if(!isset($users[$request["user"]])) return "failed";
			if(!isset($request["pass"])){
				$pass = $users[$request["user"]]["pass"];
				$users[$request["user"]]["pass"] = md5($users[$request["user"]]["pass"]);
				file_put_contents($this->db, json_encode($users));
				Apayus::lib("mailer")->setting($this->config['mail']);
				$msg = Apayus::lib("mailer")->sendMSG(array(
					"to"=> $users[$request["user"]]["email"],
					"tpl" => dirname(__FILE__)."/tpl/remember.tpl",
					"body" => array("pass" => $pass, "user"=>$request["user"])
				));
				return $msg ? "remember" : "noremember";
			}else{
				if($this->verify($users[$request["user"]], $request)) return "success";
				else return "failed";
			}
		}
		//.............................................
		public function save($request){
			$usera = $this->current(true);	
			if(isset($request['pass'])){
				if($usera["pass"] == $request['oldpass']) unset ($request['oldpass']);
				else return false;
			}
			foreach($usera as $k=>$i) if(!isset($request[$k])) $request[$k]=$i;
			$users = json_decode($this->get(), true);	
			unset($users[$usera["user"]]);
			$users[$request["user"]] = $request;
			file_put_contents($this->db, json_encode($users));
			$this->setSession($this->get($request));
			return $request;
		}
	
		public function get($request=false, $all=false){
			$result = file_get_contents($this->db);
			if(isset($request["user"])) {
				$users = json_decode($result, true);
				if(!isset($users[$request["user"]])) return false;
				$result = $users[$request["user"]];
				$result["user"] = $request["user"];
				if(!$all) unset($result["pass"]);
			}
			return $result;
		}
	
		public function del($request){		
			if(isset($request["user"])) {
				$users = json_decode($this->get(), true);
				if(!isset($users[$request["user"]])) return false;
				$result = $users[$request["user"]];
				$result["user"] = $request["user"];
				unset($users[$request["user"]]);
				file_put_contents($this->db, json_encode($users));
				return $result;
			}
			return false;
		}
	
		public function add($request){
			$users = json_decode($this->get(), true);
			$users[$request["user"]] = $request;
			file_put_contents($this->db, json_encode($users));
			return $request;
		}

		//.............................................
		public function verify($user, $request){
			$pass  = $user["pass"];
			$user["user"] = $request["user"];
			$user  = ($pass == $request["pass"]) ? $user : false;
			if($user){
				$this->setSession($user);
				return true;
			}return false;				
		}
		
		public function denied($module, $action){
			$this->header('main/index');
		}
		
		public function authorize($module, $action){
			$user = $this->getSession();
			if(!isset($this->config['security']["access"][$user['role']])) return false;
			$licence = $this->config['security']["access"][$user['role']];
			if(!is_array($licence)) return $licence;
			if(isset($licence[$module])){
				if(is_array($licence[$module])){
					if(isset($licence[$module][$action])) return $licence[$module][$action];
				}else return $licence[$module];
			}
			return false;
		}

		public function getSession(){
			if(!isset($_SESSION['user']))
				$_SESSION['user'] = array( "name"=>"Invitado", "role"=>"guest" );
			return $_SESSION['user'];
		} 

		public function setSession($user){
			$_SESSION['user'] = $user;
		} 

		public function delSession(){
			unset($_SESSION['user']); 
		} 

		public function current($all=false){
			$user = Apayus::lib("user")->getSession();
			return ($user['role']!='guest') ? $this->get($user, $all) : $user ;
		} 
	}
