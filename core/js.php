<?php
defined("_uniq_token_") or die('');

class JS {
	/**
	* @brief	Détermine si l'application est en développement ou en production
	* @var		$production	
	*/
	private static $production = false;
	
	/**
	* @brief	Dossier racine des fichiers Javascript
	* @var		$production		
	*/
	private static $base = "/static/js/";
	
	/**
	* @brief	Nom par défaut du fichier Javascript compressé qui est utilisé pour la production
	* @var		$prodJS		
	*/
	private static $prodJS = "mabileau.min.js";
	
	/**
	* @brief	Liste des modules à charger. Cela correspond au nom du dossier qu'on va inclure (tous les fichiers du dossier récursivement)
	* @var		$modules
	*/
	public static $modules = array();
	
	/**
	* @brief	Liste des fichiers indépendants à charger.
	* @var		$modules
	*/
	public static $files = array();
	
	/**
	* @brief	Charge les fichiers Javascript selon le nom de la page et selon l'état de l'application (production ou développement)
	* @param	String		$controlName	Nom de la page qui demande à inclure le Javascript
	* @return 	String		Code HTML nécessaire à l'inclusion du/des codes Javascript
	* @see 		JSLoader::$production
	*/
	public static function loadAll()
	{
		$result = "";
		if(self::$production == false) {
			$file = array();
			if(file_exists(self::$base."common.js"))
				$file[] = self::$base.'common.js';
			if(file_exists(self::$base.$controlName.".js"))
				$file[] = self::$base.$controlName.".js";
				
			
			foreach($file as $f)
				$result .= self::getHTML($f);
		}
		else 
			$result = getHTML(self::$base.self::$prodJS);
		
		return $result;
	}
	
	/**
	* @brief	Charge les fichiers Javascript 
	* @return	$String 	Code HTML représentant les fichiers javascript
	*/
	public static function load()
	{
		$result = "";
		
		foreach(self::$files as $file){
			$url = self::$base;
			if(strpos($file, "http") !== false)
				$url = "";
			
			$result .= self::getHTML($url.$file);
		}
		
		foreach(self::$modules as $module)
			self::includeDir($module, $result);
		
		
		
		return $result;
	}
	
	/**
	* Fonction récursive d'inclusion
	*/
	public static function includeDir($folder, &$result){
		try {
			$d = opendir(DOCUMENT_ROOT."/".WEBSITE.self::$base.$folder);
			while($entry = @readdir($d)) {
				if(!is_dir($folder.$entry) AND $entry != "." AND $entry != "..")
					$result .= self::getHTML(self::$base.$folder.$entry);
				else if($entry != "." AND $entry != "..")
					self::includeDir($folder.$entry."/", $result);
			}
		}
		catch (Exception $e){
			Handler::exception($this, $e);
		}
	}
	
	
	/**
	* Permet d'ajouter un lien JS ou un dossier
	*/
	public static function add($link){
		if(strpos($link, ".js") === false)
			self::$modules[] = $link;
		else {
			if(substr($link , -1, 1) != "/")
				$link .= "/";
			self::$files[] = $link;
		}
	}
	
	/**
	* @brief	Retourne le code HTML nécessaire à l'inclusion de fichier Javascript externes
	* @param	String		$url	Url du fichier javascript à inclure
	* @return 	String		Code HTML
	*/
	public static function getHTML($url)
	{
		return "<script type='text/javascript' src='".WEBSITE.$url."'></script>
		";
	}
}