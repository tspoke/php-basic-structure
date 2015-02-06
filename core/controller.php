<?php
defined("_uniq_token_") or die('');

abstract class Controller {
	protected $vars = array();
	protected $connectedOnly = true;
	protected $view = null; // array("premiere", "maDeuxiemeVue"); ou juste le nom de la vue
	protected $viewExcluded = array(); //exclusion de vues (header ou footer par exemple, sans extension '.php')
	protected $modules = array(); // liste des modules du controller. Ces modules (vues) sont intégrés par le header à l'endroit approprié => ex : include $modules['monModule'];
	protected $headers = array("Content-type" => "Content-Type: text/html; charset=UTF-8"); //peut être modifié si appel API
	
	public function render(){
		$this->vars['modules'] = $this->modules; //écrasement de variables possible ici (et volontaire) ! attention au nommage

		// rend accessible les variables dans les vues
		extract($this->vars);

		if($this->view != null) {
			$this->before();

			//on envoi les headers
			foreach($this->headers as $header)
				header($header);
			
			if(!in_array('header', $this->viewExcluded))
				require(DOCUMENT_ROOT."/views/header.php");
			
			if(is_array($this->view)){
				foreach($this->view as $view)
					require(DOCUMENT_ROOT."/views/".$view.".php");
			}
			else
				require(DOCUMENT_ROOT."/views/".$this->view.".php"); //inclusion de la vue
			
			if(!in_array('footer', $this->viewExcluded))
				require(DOCUMENT_ROOT."/views/footer.php");
			
			$this->after();
		}
		else
			Handler::error404("View isn't initialized !");
	}
	
	protected function loadModel($model){
		$basename = $model;
		
		$matches = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY); 
		$model = implode("-", $matches);

 		require_once(DOCUMENT_ROOT."/models/".strtolower($model).".php");
		$this->$basename = new $basename();
	}
	
	protected function set($vars){
		$this->vars = array_merge($this->vars, $vars);
	}
	
	public function	allowOnlyIfConnected(){
		// Vous pouvez mettre du code ici pour controler si l'utilisateur est loggé (dans index.php mettre l'appel)
	}
	
	public function getConnectedOnly(){
		return $this->connectedOnly;
	}
	
	/**
	* Fonction appelée avant tout rendu
	*/
	protected function before(){
		
	}
	
	/**
	* Fonction appelée après tout rendu
	*/
	protected function after(){
		
	}
}