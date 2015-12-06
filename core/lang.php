<?php
namespace basic\core;
defined("_uniq_token_") or die('');

/**
* @class		LANG
* @brief		Handle translation
* @example 		views/index.php
* @note 		If you need to add langages, you have to create a folder in langs/ with the extension of the langage. Then, add here in $LANGS_LIST the extension.
*/
class Lang {
 	private static $LANGS_LIST = array("en", "fr",);
 	private static $TYPES = array("models", "views"); // Files type (physical folder in langs/{lang}/)
 	private static $ERRORS = array(	"type" 		=> "LANG::Type not found", 
 									"file"		=> "LANG::File not found", 
 									"missing" 	=> "LANG::Missing", 
 									"call" 		=> "LANG::Loop detected");

	private static $dir = null; // will be automaticaly created if not modified @see setDir();
	private static $dirExceptions = array(); // Permit to generate in the fly the files exclusions ex : ["views"] = "home-views"
	private static $lang = "en"; // default lang
	private static $translations = array();
	private static $delimiter = "#{{(.*?)}}#"; // pattern to delimit variables in lang translations


	/**
	* @brief 	Set a reference langage if it exists in LANGS_LIST
	*/
	public static function setLang($lang){
		if(in_array($lang, self::$LANGS_LIST))
			self::$lang = $lang;
	}


	/**
	* @brief 	Add an exeption for folders's langs (rewrite the file name)
	*/
	public static function setDirException($key, $value){
		if(in_array($key, self::$TYPES))
			self::$dirExceptions[$key] = $value;
	}


	/**
	* @brief 	Set the reference folder
	*/
	public static function setDir($dir = null){
		if(is_null($dir))
			self::$dir = DOCUMENT_ROOT."/langs";
		else
			self::$dir = $dir;
	}


	/**
	* Get the current lang
	*/
	public static function getLang(){
		return self::$lang;
	}


	/**
	* Get all langs availables
	*/
	public static function getLangs(){
		return self::$LANGS_LIST;
	}


	/**
	* Replace variables with the custom value
	*/
	public static function replace($string, array $values){
		if (preg_match_all(self::$delimiter, $string, $matches)) {
			foreach ($matches[1] as $i => $varname) {
				$val = "???";
				if(isset($values[$varname]))
					$val = $values[$varname];

				$string = str_replace($matches[0][$i], $val, $string);
			}
		}
		return $string;
	}

	/**
	* @brief 	Get the translation for the requested keys
	* @param 	$type 				File type who ask for the translation (eg : view, model, ...)
	* @param 	$file 				File source name who call ('base', 'messagerie', ...)
	* @param 	$key 				Keys to get the translation, Can be a string or an array !
	* @param 	$valuesToReplace 	Array with values to replace patterns in the translation
	* @param 	$index 			 	Current index if $key is an array
	*
	* @example  $valeurs = array('unite' => "TEST", "unite2" => "TEST2"); 
	*			echo LANG::get("models", "message", array("origin", "delete"), $valeurs); // target on $trans['origin']['delete'] and replace patterns with $valeurs
	*			
	*			echo LANG::get("models", "message", "origin")['send']; // work too !
	*/
	public static function get($type, $file, $key, array $valuesToReplace = null, $index = 0){
		self::load($type, $file);

		if(is_array($key) && isset($key[$index + 1])){
			$index++;
			return self::get($type, $file, $key, $valuesToReplace, $index);
		}

		if(array_key_exists($type, self::$dirExceptions))
			$type = self::$dirExceptions[$type];

		$url = self::$lang."/".$type."/".$file.".php";

		$string = self::$ERRORS['missing'];
		if(is_array($key) && isset(self::$translations[$url])){ //gestion array
			$string = self::$translations[$url];
			foreach($key as $k){
				if(isset($string[$k]))
					$string = $string[$k];
				else{
					$string = self::$ERRORS['missing'];
					break;
				}
			}
		}
		else if(isset(self::$translations[$url][$key])) //si la clef existe (non array)
			$string = self::$translations[$url][$key];

		//fin traitement
		if(!is_null($valuesToReplace)) // si $valueToReplace est définie, on remplace directement les valeurs
			$string = self::replace($string, $valuesToReplace);
		return $string;
	}


	/**
	* @brief 	Load a langage file
	* @todo 	Charger un fichier de langue par défaut si on ne trouve la langue existe pas (en)
	*/
	public static function load($type, $file){
		if(is_null(self::$dir))
			self::setDir();

		if(!in_array($type, self::$TYPES))
			return self::$ERRORS['type'];

		// Rewriting of the name if an exception exists
		if(array_key_exists($type, self::$dirExceptions))
			$type = self::$dirExceptions[$type];

		$url = self::$lang."/".$type."/".$file.".php";

		// If we already load this file
		if(isset(self::$translations[$url]))
			return;

		if(!file_exists(self::$dir."/".$url)) //ex : langs/fr/home.php
			return self::$ERRORS['file'];

		$url = self::$lang."/".$type."/".$file.".php";

		// Ajout
		self::$translations[$url] = include self::$dir."/".$url;
	}
}