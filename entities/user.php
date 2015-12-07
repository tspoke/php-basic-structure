<?php
namespace basic\entities;
defined("_uniq_token_") or die('');

class User extends \basic\core\Entity {
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