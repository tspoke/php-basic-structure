<?php
require_once "Config.inc";

class Connection {
	private static $m_instance = null;
	private $bdd = null;
	
	private function __construct(){ 
		$this->bdd = $this->connect();
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
	
	public static function instance(){
		if(self::$m_instance == null){
			self::$m_instance = new Connection();
		}
		return self::$m_instance->bdd;
	}
}