<?php
namespace basic\entities;
defined("_uniq_token_") or die('');

class User extends \basic\core\Entity {
	protected $jsonException = array("pass"); // disallow this attribut to be extracted in toArray() parent method

	protected $id;
	protected $email;
	protected $pass;

	public function getId(){
		return $this->id;
	}
	public function setId($val){
		$this->id = $val;
	}
	public function getEmail(){
		return $this->email;
	}
	public function setEmail($val){
		$this->email = $val;
	}
	public function getPass(){
		return $this->pass;
	}
	public function setPass($val){
		$this->pass = $val;
	}
}