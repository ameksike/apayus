<?php
/*
 * @author: Apayus CS Team
 * @mail: apayus.cs@gmail.com
 * @made: 15/3/2013
 * @update: 15/3/2013
 * @description: This is extends of PHPMailer for Admin
 * @require: PHPMailer, Notary
 *
 * */
include_once dirname(__FILE__)."/PHPMailer/PHPMailerAutoload.php";
class Mailer extends PHPMailer
{
	public function loadcfg($path="cfg/"){
		$cfg = @include $path."config.php";
		$this->cfg = is_array($cfg) ? isset($cfg['mail']) ? $cfg['mail'] : $cfg : array();
	}

	public function __construct($option='cfg/'){
		if(is_array($option)) $this->cfg = $option;
		else $this->loadcfg($option);
		$this->setting();
	}

	public function setting($option=0){
		$this->cfg = (is_array($option)) ? $option : $this->cfg;
		$this->isHTML(true);
		$this->CharSet = 'utf-8'; 
		$this->WordWrap = 50; 
		$this->prepare($this->cfg);
		Apayus::lib("notary")->path( isset($this->cfg["tpl"]) ? $this->cfg["tpl"] : 'tpl/default.tpl');
	}

	public function fill($dta, $action){
		if(!is_array($dta)) $this->{$action}($dta, $dta);
		else foreach($dta as $i) $this->fill($i, $action);
	}

	public function dispatch($key, $value){
		switch($key){
			case "to": 	$this->fill($value, "addAddress"); break;
			case "reply": 	$this->fill($value, "addReplyTo"); break;
			case "cc": 	$this->fill($value, "addCC"); break;
			case "bcc": 	$this->fill($value, "addBCC"); break;
			case "attach": 	$this->fill($value, "addAttachment"); break;
			case "tpl": 	Apayus::lib("notary")->path($value); 

			break;
			case "subject": $this->Subject = $value; break;
			case "body": 	$this->Body = $value; break;
			case "altbody": $this->AltBody = $value; break;
			case "from": 	
					$from = is_array($value) ? $value[0] : $value;
					$fromname = is_array($value) ? $value[1] : $value;
					$this->setFrom($from, $fromname); 
			break;
			case "fromname": $this->FromName = $value; break;
			case "username": $this->Username = $value; break;
			case "password": $this->Password = $value; break;
			case "host": 	 $this->Host = $value; break;
			case "port": 	 $this->Port = $value; break;
			case "driver": 	 $this->setDriver($value); break;
			case "timeout":  $this->setTimeout($value); break;
			case "timelimit":  $this->Timelimit($value); break;
			default: $this->{$key} = $value; break;
		}
	}

	public function prepare($option){
		foreach($option as $k=>$i) $this->dispatch($k, $i);
	}

	public function setDriver($type="mail"){
		$type = strtolower($type);
		switch($type){
			case "qmail": $this->isQmail(); break;
			case 'sendmail': $this->isSendmail(); break;
			case 'mail': $this->isMail(); break;
			case 'smtp': 
				$this->isSMTP(); 
				$this->SMTPAuth = true;  
				$this->SMTPSecure = 'tls'; 
			break;
		}
	}

	public function sendMSG($option){
		$this->prepare($option);
		$this->Body = Apayus::lib("notary")->compile($this->Body);
		return parent::send();
	}
}
