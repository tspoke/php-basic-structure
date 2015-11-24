<?php
namespace CustomAuth;

/**
* Implementation of the basic HTTP connection auth
* @warning 		You shouldn't use this authentification method, it's unsecured. Try DigestAuth instead.
*/
abstract class BasicAuth implements IAuth {

	/**
	* Please provide an implementation of this method in a derived class
	* @note 	Return null if failed, else the user found
	*/
	protected abstract function findUser($login, $password);

	final public function auth(array $datas = null){
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
		    return $this->fail();
		}
		else {
			$login = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];

			$user = $this->findUser($login, $pass);
			
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