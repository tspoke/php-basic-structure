<?php
defined("_uniq_token_") or die('');

class HomeController extends Controller {

	protected $connectedOnly = false;

	public function index(){
		$this->view = "index";
	}
}