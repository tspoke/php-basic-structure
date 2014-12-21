<?php
defined("_uniq_token_") or die('');

class HomeController extends Controller {

	public function index($loginStatus = null){
		$this->view = "index";
	}
	
	/**
	* Exemple de connexion
	*/
	public function login(){
		$this->loadModel('User');

		if(!isset($_POST['email']) OR !isset($_POST['pass']))
			Handler::url("home", "index", "missing");
		
		$user = $this->User->find(array(
			'email' => $_POST['email'],
			'pass' => Tools::hash($_POST['pass']) // hash unique que vous DEVEZ éditer dans Tools.php
		));
		
		if(empty($user))
			Handler::url("home", "index", "error");
			
		//on connecte la session
		$_SESSION['user'] = $user;
		Handler::url("home", "index", "ok"); 
	}

	public function logout(){
		session_unset();
		session_destroy();
		Handler::reload();
	}
}