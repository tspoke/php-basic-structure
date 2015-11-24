<?php
namespace CustomAuth;

/**
* Custom auth autoloader
*/
class Autoload {
	public static function loader($class) {
		$path = "vendors/".$class.".php";
		if(file_exists($path)){
			require_once($path);
		}
	}
}

spl_autoload_register(array('\CustomAuth\Autoload', 'loader'));