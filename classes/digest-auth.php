<?php
namespace basic\classes;

/**
* Implementation of the Digest HTTP connection auth
*/
class DigestAuth extends \CustomAuth\DigestAuth {
	protected function getUserPassword($login, &$user) {
		// try to retrieve user from db with this login (email)
		$userModel = new \User();
		$user = $userModel->find(array(
			"email" => $login
		));
		if($user != null)
			return $user["password"];
		return null;
	}
}