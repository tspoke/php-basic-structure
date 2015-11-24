<?php
defined("_uniq_token_") or die('');

class Autoload {
	public static $folders = array(
		"models"  => "models", 
		"core"    => "core", 
		"classes" => "classes"
	);
	
	public static function loader($class) {
		//si on a une classe que en majuscule, on ne passe pas dans la découpe.
		if(strtoupper($class) != $class) {
			$basename = $class;
			
			$matches = preg_split('/(?=[A-Z])/', $class, -1, PREG_SPLIT_NO_EMPTY); 
			$class = implode("-", $matches);
		}
		$class = str_replace('\\', '/', strtolower($class)).".php";
		
		foreach(self::$folders as $folder){
			$path = $folder."/".$class;
			if(file_exists($path)){
				require_once($path);
				break;
			}
		}
	}
	
	public static function setFolders(array $arr){
		self::$folders = $arr;
	}
	
	public static function setFolder($key, $folder){
		self::$folders[$key] = $folder;
	}
}

spl_autoload_register(array('Autoload', 'loader'));

// add others autoload here
include DOCUMENT_ROOT."/vendors/CustomAuth/autoload.php";