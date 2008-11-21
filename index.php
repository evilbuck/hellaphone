<?php

define('ROOT_DIR', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require ROOT_DIR . DS . 'include' . DS . 'setup.php';


try {
	if(empty($_REQUEST['c']))	$_REQUEST['c'] = 'newz';
	
	$controller_name = Inflector::Classify($_REQUEST['c']) . '_Controller';
	$controller_file = Inflector::underscore($_REQUEST['c']);
	include CONTROLLER_DIR . DS . $controller_file . '_controller.php';
	$controller = new $controller_name();
} catch (Exception $e) {
	echo $e;
}