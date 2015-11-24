<?php
namespace CustomAuth;

/**
* Implementation of the Digest HTTP connection auth. This implementation use a plaintext password from the DB, feel free
* to modify it to use crypted passwords.
*
* More informations to use correctly this system with crypted passwords
* http://stackoverflow.com/questions/18551954/encrypted-password-in-database-and-browser-digest-auth
*/
abstract class DigestAuth implements IAuth {
	private $realm = "Please provide credentials"; // if you use crypted password, this variable must not be changed

	/**
	* Please provide an implementation of this method in a derived class
	* @note 	Return null if failed, else the password
	*/
	protected abstract function getUserPassword($login, &$user);

	/**
	* This version use a plaintext password to rebuild the hash, see header informations.
	*/
	final public function auth(array $datas = null){
		// try to extract datas from PHP_AUTH_DIGEST
		if (empty($_SERVER['PHP_AUTH_DIGEST']) || !($data = $this->httpDigestParse($_SERVER['PHP_AUTH_DIGEST'])))
		    return $this->fail();

		$userOut = null; // to store user found
		$passwordFromDb = $this->getUserPassword($data["username"], $userOut);
		if($passwordFromDb === null)
			return $this->fail();

		// we have all our datas - we need to rebuild a hash with the password retrieved from the db and compare the hashes
		$A1 = md5($data['username'] . ':' . $this->realm . ':' . $passwordFromDb);
		$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
		$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

		if ($data['response'] != $valid_response) // we compare both hashes
		    return $this->fail();

		return $userOut;
	}

	private function httpDigestParse($txt){
	    // protection against missing datas
	    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
	    $data = array();
	    $keys = implode('|', array_keys($needed_parts));
	 
	    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

	    foreach ($matches as $m) {
	        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
	        unset($needed_parts[$m[1]]);
	    }

	    return $needed_parts ? false : $data;
	}


	private function fail($message = null){
	    header('HTTP/1.1 401 Unauthorized');
		header('WWW-Authenticate: Digest realm="'.$this->realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($this->realm).'"');
	    if($message)
	    	echo $message;
	    else 
	    	echo 'Please provide credentials to access API';
	    exit;
	    //return null; // depending on your need you may want to return a value instead to exit() the app
	}
}