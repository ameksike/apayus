<?php
	include 'ApayusEngine.php';
	$apayus = new ApayusEngine(__DIR__);
	$apayus->listen();
