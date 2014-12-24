<?php
require_once "Config.inc";

/**
* Connection Singleton
* TODO 	L'utilisation d'un singleton n'est pas vraiment approprié. Il vaudrait mieux demander la connexion et la relâcher à chaque requête.
*/
class Connection {
	private static $m_instance = null;
	private $db = null;
	
	private function __construct(){ 
		$this->db = $this->connect();
	}
	
	private function connect(){
		try {
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
			$pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
			return new PDO('mysql:host='.SQL_HOST.'; dbname='.SQL_DBNAME, SQL_USER, SQL_PASS, $pdo_options);
		}
		catch (Exception $e){
			die("Impossible d'établir une connexion à la base de donnée.");
		}
	}
	
	// get the connection in a static/singleton way
	public static function instance(){
		if(self::$m_instance == null){
			self::$m_instance = new Connection();
		}
		return self::$m_instance->db;
	}
}