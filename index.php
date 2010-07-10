<?php
	
	define('EEC_BASE_PATH', './');
	
	include 'Core.php';
	
	
	
	$test = Core::getInstance();
	var_dump($test);
	//var_dump($_GET);
	//$test->setUrl("so/m/e/M/o/dule", "", "1/1/1/1/");
	//$test->setUrl();
	var_dump($test->getUrl());
?>
