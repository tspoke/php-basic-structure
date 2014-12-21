<?php
defined("_3gm_token_") or die('');

define("PROD", false);

//routes
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
define("WEBROOT", DOCUMENT_ROOT.str_replace("/index.php", "", $_SERVER['SCRIPT_FILENAME']));
define("WEBSITE", str_replace(DOCUMENT_ROOT, "", WEBROOT));
define("URL", "http://example.fr");

require(DOCUMENT_ROOT."/core/autoload.php");