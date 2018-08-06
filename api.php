<?php

require_once('autoload.php');

$path = explode('/', $_GET['path']);

foreach ($path as $value) {
	if (empty($route[$value]) === true) {
		http_response_code(404);
		exit("Error: The path is wrong.");
	} else {
		$route = $route[$value];
	}
}

$class = substr($route, 0, strpos($route, '->'));
$method = substr($route, strpos($route, '->') + 2);

if (gettype($route) === 'string' && strpos($route, '->') !== false && file_exists('controller/'. $class .'.php') === true) {
	require_once('controller/'. $class .'.php');
} else {
	exit("Error: Route error.");
}

if (isset($_POST) === true) {
	$params = $_POST;
} else if (isset($_GET) === true) {
	$params = $_GET;
	unset($params['path']);
}

if (class_exists($class) === true) {
	$object = new $class();
	if (method_exists($object, $method) === true) {
		$json = $object->$method($params);
		http_response_code($json->code);
		echo json_encode($json);
	}
} else {
	http_response_code(400);
	exit("Error: Function not found.");
}

?>