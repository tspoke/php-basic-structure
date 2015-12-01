<?php
namespace basic\core;
defined("_uniq_token_") or die('');

abstract class ConnectionHandler {
	protected $db = null;

	public function __construct(){
		$this->db = Connection::instance();
	}
	
}