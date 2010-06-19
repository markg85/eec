<?php
	
	define('EEC_BASE_PATH', './');
	
	include 'Core.php';
	
	
	
	$test = Core::getInstance();
	echo '<pre>';
	print_r($test);
	print_r($_GET);
	echo '</pre>';
	
?>