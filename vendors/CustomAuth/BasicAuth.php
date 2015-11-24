<?php
namespace CustomAuth;

/**
* Implementation of the basic HTTP connection auth
*/
class BasicAuth extends \ConnectionHandler implements IAuth {
	public function __construct(){
		parent::__construct();
	}

	public function auth(array $datas = null){
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		    return $this->fail();
		}
		else {
			$login = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];

			$userModel = new \User();
			$user = $userModel->find(array(
				"email" => $login,
				"pass" => \Tools::hash($pass)
			));
			
			if($user === null)
				return $this->fail();

			return $user;
		}
	}


	private function fail(){
		header('WWW-Authenticate: Basic realm="Please provide credentials"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo 'Please provide credentials to access API';
	    exit;
	    //return null; // depending on your need you may want to return a value instead to exit() the app
	}
}