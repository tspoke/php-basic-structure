<?php
namespace basic\models;
defined("_uniq_token_") or die('');

class User extends \basic\core\Model {
	protected $table = "user";
	
	// you can add some logic like this here (or elsewhere), feel free to move/modify. Dependencies : in index.php
	public static function isConnected(){
		if(isset($_SESSION['user']) AND isset($_SESSION['user']['user_id']) AND $_SESSION['user']['user_id'] > 0)
			return true;
		return false;
	}
}