<?php
	$config["mail"]["host"]		= "srq-cc.com"; 		//... servidor o proveedor de correo
	$config["mail"]["username"]	= "reservastucita";		//... campo de usuario de una cuenta activa en el servidor de correo
	$config["mail"]["password"]	= "010414";				//... contraseña para el usuario activo en el servidor de correo
	$config["mail"]["from"]		= "reservastucita@srq-cc.com";	//>>reservastucita@srq-cc.com ... $emailSender_, cuenta que emite el correo 
	//$config["mail"]["reply"]	= "reservastucita@srq-cc.com";	//... cuenta que recibe las respuestas
	$config["mail"]["fromname"]	= "Administrador";				//... alias o sobre nombre para el que emite el correo
	//$config["mail"]["cc"]		= "reservastucita@santarosadequivescc.com"; 	//... $emailReceiptCc_, cuenta que recibe copia de los correos
	//$config["mail"]["bcc"]	= "apayus.cs@gmail.com";
	$config["mail"]["driver"]	= "mail";

	$config["db"]["host"]		= "localhost";			//... servidor o proveedor de bases de datos
	$config["db"]["user"]		= "root";			//... usuario de una cuenta activa en el servidor de bases de datos
	$config["db"]["pass"]		= "";			//... contraseña requerida para la cuenta activa en el servidor de bases de datos
	$config["db"]["name"]		= "srqcc_j25";			//... nombre de la base de datos a la cual debe conectarse
	
	$config['tpl']["html"]      = "/client/html/";
	$config['tpl']["php"]       = "/server/tpl/";
	
	$config["sys"]["redirect"]	= "http://www.santarosadequivescc.com"; //... url definida para el redireccionamiento en el sistema
	$config["sys"]["menu"][0]["label"] = "Inicio";
	$config["sys"]["menu"][0]["module"] = "main";
	$config["sys"]["menu"][0]["url"] = "main/home";
	$config["sys"]["menu"][0]["ico"] = "glyphicon glyphicon-home";
	
	$config["sys"]["menu"][1]["label"] = "Reservas";
	$config["sys"]["menu"][1]["module"] = "reservas";
	$config["sys"]["menu"][1]["url"] = "reservas/index";
	$config["sys"]["menu"][1]["ico"] = "glyphicon glyphicon-tags";
		
	$config["sys"]["menu"][2]["label"] = "Registro";
	$config["sys"]["menu"][2]["module"] = "registro";
	$config["sys"]["menu"][2]["url"] = "registro/index";
	$config["sys"]["menu"][2]["ico"] = "glyphicon glyphicon-tags";

	$config['security']["manager"]	= "user";
	$config['security']["access"]["guest"]['main'] = true;
	$config['security']["access"]["guest"]['user']['dologin'] = true;
	$config['security']["access"]["guest"]['user']['login'] = true;
	$config['security']["access"]["admin"] = true; 
	return $config;
