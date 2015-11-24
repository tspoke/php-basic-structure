<?php
defined("_uniq_token_") or die('');

/**
* This is an basic example API class to show you how to create it.
*/
class Apiv1Controller extends Api {
	/**
	* @Example 	API 	 method
	* 			URL 	 /apiv1/user
	*/
	public function user(){
		$this->setSuccess();
	}

	private function setSuccess(){
		$this->response["code"] = 200;
		$this->response["status"] = "success";
	}

	private function setError($message = "Unknow error"){
		$this->response["code"] = 0;
		$this->response["status"] = "error";
		$this->response["message"] = $message;
	}

	// Default method to handle params number error properly.
	public function defaultParamsError(){
		$this->setError("The call is incorrect");
	}

	// for example, using a vendors library called CustomAuth
	public function usingCustomAuth(){
		$auth = new DigestAuth(null);
		$auth->auth();
	}

}