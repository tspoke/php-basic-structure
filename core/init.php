<?php
defined("_uniq_token_") or die('');

/**
* Absolute system path to website folder
* @value 	 /var/www/my-website-folder
*/
define("DOCUMENT_ROOT", realpath(__DIR__."/.."));

/**
* Debug status
*/
define("PROD", false);
define("DEV", !PROD); // shortcut

/**
* Absolute or relative path to website folder
* @example 		http://example.com 			Domain of you website
* @example 									Empty if you want to use relative path
* @example 		/sub-folder					In case of sub-folder hosting
*/
define("URL", "dev-izymatch");

require(DOCUMENT_ROOT."/core/autoload.php");

// Show errors if dev-mode activate
if(DEV){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}