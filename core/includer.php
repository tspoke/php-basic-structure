<?php
defined("_uniq_token_") or die('');

class Includer {

	/**
	* @brief	Dossier racine des fichiers Javascript
	* @var		$production		
	*/
	private static $base = array();
	private static $baseRoot = "/static/";

	/**
	* @brief	Folders that they will be include with all their files
	* @var		$modules
	*/
	private static $modules = array();
	
	/**
	* @brief	Independant files who will be include
	* @var		$modules
	*/
	private static $files = array();


	/**
	* Add a file or a folder to the include list and auto-create the list and base structure
	*/
	public static function add($type, $link){
		$type = strtolower($type);

		if(!isset(self::$files[$type])){ // creation if first
			self::$files[$type] = array();
			self::$modules[$type] = array();
			self::$base[$type] = self::$baseRoot.$type."/";
		}

		if(strpos($link, ".".$type) === false){
			if(substr($link , -1, 1) != "/")
				$link .= "/";
			self::$modules[$type][] = $link;
		}
		else {
			self::$files[$type][] = $link;
		}
	}
	

	/**
	* @brief	Return the HTML code for all preloaded files
	* @param 	String  File type
	* @return	String 	HTML code
	*/
	public static function load($type)
	{	
		$type = strtolower($type);
		if(!isset(self::$files[$type]))
			return;

		$result = "";
		$typeFiles = self::$files[$type];

		foreach($typeFiles as $file){
			$url = self::$base[$type];
			if(strpos($file, "http") !== false)
				$url = "";
			$result .= self::getHTML($type, $url.$file);
		}
		
		foreach(self::$modules[$type] as $module)
			self::includeDir($type, $module, $result);
		
		return $result;
	}


	/**
	* @brief	Retourne le code HTML nécessaire à l'inclusion de fichier Javascript externes
	* @param	String		$url	Url du fichier javascript à inclure
	* @return 	String		Code HTML
	*/
	public static function getHTML($type, $url)
	{
		$type = strtolower($type);
		if($type == "js")
			return "<script type='text/javascript' src='".URL.$url."'></script>
			";
		else if($type == "css")
			return "<link href='".URL.$url."' rel='stylesheet' type='text/css' media='screen' />
			";
		return "Include type error code HTML";
	}
	

	/**
	* Include recursively all files
	*/
	public static function includeDir($type, $folder, &$result){
		try {
			$d = opendir(DOCUMENT_ROOT.self::$base[$type].$folder);
			while($entry = @readdir($d)) {
				if(!is_dir($folder.$entry) AND $entry != "." AND $entry != "..")
					$result .= self::getHTML($type, self::$base[$type].$folder.$entry);
				else if($entry != "." AND $entry != "..")
					self::includeDir($type, $folder.$entry."/", $result);
			}
		}
		catch (Exception $e){
			Handler::exception($this, $e);
		}
	}
	
}