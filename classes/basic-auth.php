<?php
namespace basic\classes;
/**
* Implementation of the basic HTTP connection auth
*/
class BasicAuth extends \CustomAuth\BasicAuth {
	
	protected function findUser($login, $pass){
		$userModel = new \basic\models\User();
		$user = $userModel->find(array(
			"email" => $login,
			"pass" => \basic\core\Tools::hash($pass)
		));

		return $user;
	}
}