<?php
defined("_uniq_token_") or die('');
define("PROD", false);

/**
* Debug options & tools
*/
define("PROD", false);

/**
* Absolute system path to website folder
* @value 	 /var/www/my-website-folder
*/
define("DOCUMENT_ROOT", realpath(__DIR__."/.."));

/**
* Absolute or relative path to website folder
* @example 		http://example.com 			Domain of you website
* @example 									Empty if you want to use relative path
* @example 		/sub-folder					In case of sub-folder hosting
*/
define("URL", "");

require(DOCUMENT_ROOT."/core/autoload.php");

// DEBUG
if(PROD){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}