<?php
namespace basic\core;
defined("_uniq_token_") or die('');

class Handler {

	/**
	* Génère une erreur 404
	*/
	public static function error404($msg = "Undefined"){
		header('HTTP/1.0 404 Not Found');
		
		echo "<h1>404 Not Found</h1>";
		echo Tools::secure($msg);
		
		exit();
	}
	
	/**
	* Génère une exception
	*/
	public static function exception($class, Exception $exception){
		echo $class." : ".$exception->getMessage();
	}


	/**
	* Gère une erreur de code
	*/
	public static function error($msg){
		die($msg);
	}
	
	/**
	* Recharge la page courante
	*/
	public static function reload(){
		self::back();
	}

	/**
	* Redirige vers le controlleur pointé avec les options
	*/
	public static function url($control, $action = "index", $params = null, $folder = null){
		$url = array();
		
		if(is_null($folder) && WEBSITE != "")
			$url[] = WEBSITE;
		else if(!is_null($folder) && !empty($folder))
			$url[] = $folder;

		$url[] = $control;
		$url[] = $action;

		if(!is_null($params) && !empty($params)){
			if(is_array($params)){
				foreach($params as $value)
					$url[] = $value;
			}
			else
				$url[] = $params;
		}

		header('Location: /'.implode("/", $url));
		exit();
	}


	/**
	* Retourne à la page d'appel (via le referer)
	*/
	public static function back($params = null){
		$url = "index.php";
	
		if(isset($_SERVER['HTTP_REFERER']))
			$url = $_SERVER['HTTP_REFERER'];
		
		if(isset($params) AND !empty($params)){
			$url .= "?";
			if(is_array($params)){
				$list = "";
				foreach($params as $key => $value){
					if($list != "")
						$list .= "&";
					$list .= $key."=".$value;
				}
			}
			else
				$list = "data=".$params;
			$url .= $list;
		}
		
		header('Location: '.$url);
		exit();
	}
}