<?php
	
	define('EEC_BASE_PATH', __DIR__ . '/');
	define('EEC_MODULE_PATH', __DIR__ . '/modules/');
	
	include 'Core.php';
	
	
	
	$test = Core::getInstance();
	var_dump($test);
	//var_dump($_GET);
	//$test->setUrl("so/m/e/M/o/dule", "", "1/1/1/1/");
	//$test->setUrl();
	//var_dump($test->handleUrl());
	
	var_dump($test->validateValue("Just some string...", "isString"));

	$test->get("template_manager")->init();
	$test->get("template_manager")->addDataContainer("test");
	$test->get("template_manager")->addTemplateFile("test.tpl");
	$test->get("template_manager")->output("test.tpl", "test");
	$test->handleUrl();
?>
