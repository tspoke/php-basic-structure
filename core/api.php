<?php
defined("_uniq_token_") or die('');

class Api extends Controller {
	// You should customize this structure depending on you need
	protected $response = array("status" => "success", "code" => 200, "message" => "", "data" => array());

	/**
	* Default index page for all APIs
	* @note  	This method act as an controller method.
	*/
	public function index(){
		$this->response["message"] = "Welcome";
	}

	/**
	* @Override 	Replace the render method for basic controller
	*/
	public function render(){
		$this->headers["Content-type"] = "Content-Type: application/json; charset=UTF-8";
		$this->sendHeader();

		$this->before();
		$this->response["data"] = $this->vars;
		echo json_encode($this->response);
		$this->after();
	}
}