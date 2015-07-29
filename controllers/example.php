<?php
defined("_uniq_token_") or die('');

/**
* Example class
* Every class MUST extends Controller
* Feel free to remove it :)
*/
class ExampleController extends Controller {

	public function index($param = null){ // you can add params in the url. eg : your-url/example/test => will pass "test" here in $param
		
		// These 2 lines are commented because they cause an errors (files don't exist)

		// Includer::add("js", "test.js"); // example to add a JS file only for this call
		// Includer::add("js", "my-folder"); // include all JS files that my-folder contains

		$this->view = "index"; // It's the file name who will be displayed. It is located in views/
	}


	/**
	* Connection example
	* @url 		your-url.com/example/login
	*/
	public function login(){
		$this->loadModel('User'); // this line create a class. You can access this class with $this->User after :)

		if(!isset($_POST['email']) OR !isset($_POST['pass'])) // you can get POST or GET datas as you always did
			Handler::url("home", "index", "missing");
		
		// this is a basic Mysql call to get user datas
		//@see 	core/Model for more details
		$user = $this->User->find(array(
			'email' => $_POST['email'],
			'pass' => Tools::hash($_POST['pass']) // hash unique que vous DEVEZ éditer dans Tools.php
		));
		
		if(empty($user))
			Handler::url("home", "index", "error"); // redirection to your-url.com/home/index/error
			
		// SESSIONS are the same
		$_SESSION['user'] = $user;
		Handler::url("home", "index", "ok"); // Redirection
	}
}