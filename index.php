<?php
	
	include '../Config.php'; // should be in the projects folder, not in EEC..
	include 'Core.php';
	
	
	
	$test = Core::getInstance();
	//var_dump($test);
	//var_dump($_GET);
	//$test->setUrl("so/m/e/M/o/dule", "", "1/1/1/1/");
	//$test->setUrl();
	//var_dump($test->handleUrl());
	
	//var_dump($test->validateValue("Just some string...", "isString"));

	$test->get("template_manager")->init();
	$test->get("template_manager")->addDataContainer("test");
	$test->get("template_manager")->addTemplateFile("test.tpl");
	$test->get("template_manager")->output("test.tpl", "test");
	$test->handleUrl();
	
	//$test->get("acl")->addRole(new EEC_ACL_Role("admin"));
	//$test->get("acl")->addResource(new EEC_ACL_Resource("categories"));
	
	//$test->get("acl")->revoke("admin", "files", array("CRUD"));
	//$test->get("acl")->grant("admin", "categories", array("C"));
	
	var_dump($test->get("acl")->isAllowed("admin", array("files", "categories"), array("create")));
	
?>
