<?php
namespace basic\controllers;
defined("_uniq_token_") or die('');

class HomeController extends \basic\core\Controller {

	protected $connectedOnly = false;

	public function index(){
		$this->view = "index";

		/*
		$this->loadModel("User");
		$obj = $this->User->find(['id' => 1]);
		$this->set("obj", $obj);
		*/
	}
}