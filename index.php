<?php
// debug - delete this 2 lines in PROD
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("_uniq_token_", TRUE);
include_once $_SERVER['DOCUMENT_ROOT']."/core/init.php";

//parsing
$params = explode('/', $_GET['p']);
$controller = isset($params[0]) ? $params[0] : Handler::error404("No get datas");
$action = !empty($params[1]) ? $params[1] : 'index';

//including
if($controller == "")
	$controller = "home";

$baseControllerName = $controller;

if(!file_exists('controllers/'.$controller.'.php'))
	Handler::error404("Control doesn't exist");

session_name("yourSessionName");
session_start();

require('controllers/'.$controller.'.php');
$controller .= "Controller";
$controller = new $controller();

if(method_exists($controller, $action)){
	unset($params[0]);
	unset($params[1]);
	
	try {
		$method = new ReflectionMethod($controller, $action);
		$numberOfRequiredParams = $method->getNumberOfRequiredParameters ();
		$numberOfParams = $method->getNumberOfParameters ();
		$nbr = count($params);
		
		if($nbr >= $numberOfRequiredParams && $nbr <= $numberOfParams){ //lancement app	
			call_user_func_array(array($controller, $action), $params);
			$controller->render();
		}
		else {
			Handler::error404("Impossible d'appeler la fonction cible avec les paramètres passés");
		}
	}
	catch (Exception $e){
		Handler::error404("PHP Exception : ".$e);
	}
}
else
	Handler::error404("Unhandled : the method doesn't exists in the controller => ".$action);