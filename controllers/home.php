<?php
defined("_uniq_token_") or die('');

class HomeController extends Controller {

	public function index(){
		$this->view = "index";
	}
}