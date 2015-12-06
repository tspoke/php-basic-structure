<?php
namespace basic\classes;
/**
* Implementation of the basic HTTP connection auth
*/
class BasicAuth extends \CustomAuth\BasicAuth {
	
	protected function findUser($login, $pass){
		$userModel = new \User();
		$user = $userModel->find(array(
			"email" => $login,
			"pass" => \Tools::hash($pass)
		));

		return $user;
	}
}