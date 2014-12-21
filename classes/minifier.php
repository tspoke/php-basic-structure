<?php
defined("_uniq_token_") or die('');

/**
* Classe qui permet de minifier des fichiers JS ou CSS via une API en ligne
*/
class Minifier {
	private static $URL = array("js" => "http://javascript-minifier.com/raw", "css" => "http://cssminifier.com/raw");
	private static $baseDirectory = "";
	private static $sourcesDirectories = array("js", "css");

	/**
	* Demande la compression de tous les fichiers CSS/JS contenu dans le dossier où à lieu l'appel
	* @param 	String 		$addToBasePath 		Chemin vers le dossier de statiques
	* @param 	boolean 	$combine 			Demande de combiner tous les JS en un seul gros JS. Non implémenté car inutile sur ce projet.
	* @return 	void
	*/
	public static function minifyAllFiles($addToBasePath = "/static", $combine = false){
		self::$baseDirectory = DOCUMENT_ROOT.$addToBasePath;

		foreach(self::$sourcesDirectories as $dir){
			self::includeDir($dir, $files);

			foreach($files as $file){
				$content = file_get_contents($file);
				$minified = self::minify(self::$URL[$dir], $content);

				if($minified === null)
					continue;

				if(PROD)
					file_put_contents($file, $minified); //on remplace le fichier si on est en prod !
				else { //sinon on génère des fichiers .min. pour les tests
					$name = str_replace(".".$dir, "", $file).".min.".$dir; //path/to/file/css/myFile.min.css   => uniquement pour tester ces fichiers !
					file_put_contents($name, $minified);
				}
			}
		}
	}


	/**
	* Minify avec un appel distant sur une API via POST
	* @return  		La version minifié en String
	*/
	public static function minify($url, $datas){
		$result = null;

		$data = array('input' => $datas);
		$options = array(
			'http' => array(
			    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			    'method'  => 'POST',
			    'content' => http_build_query($data),
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		if($result === false)
			$result = null;

		return $result;
	}


	/**
	* Fonction récursive d'inclusion
	* @return 	Ajoute à &$result les path de chaque fichier (path complet)
	*/
	public static function includeDir($folder, &$result){
		try {
			$d = opendir(self::$baseDirectory."/".$folder);
			while($entry = @readdir($d)) {
				if(!is_dir($folder.$entry) AND $entry != "." AND $entry != "..")
					$result[] = self::$baseDirectory."/".$folder.$entry;
				else if($entry != "." AND $entry != "..")
					self::includeDir($folder.$entry."/", $result);
			}
		}
		catch (Exception $e){
			Handler::exception($this, $e);
		}
	}
}