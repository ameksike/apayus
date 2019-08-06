<?php
	include 'lib/apayus/server/Apayus.php';
	Apayus::lib("engine")->setting(dirname(__FILE__));
	echo Apayus::lib("engine")->listen();
