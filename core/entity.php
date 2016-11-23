<?php
namespace basic\core;
defined("_uniq_token_") or die('');

/**
* @author 	Thibaud GIOVANNETTI
*/
abstract class Entity {
	protected $jsonFields = null; // this attribut is automaticly generated when you transform an array to an object
	protected $jsonException = null; // array("attribut", "attribut2")

	/**
	* @brief        Constructeur par défaut
	* @param        array   $datas          Tableau optionnel de valeurs à utiliser pour la création de l'objet
	* @return       Void
	*/
	public function __construct($datas = null){
		if(isset($datas) AND $datas != null)
			$this->hydrate($datas);
	}

	/**
	* @brief        Hydrate l'objet en assignant les valeurs, du tableau @a $datas renseigné, aux attributs.
	*                       Cela ne fonctionne que si les setters existent pour ces valeurs.
	* @param        array   $datas  Tableau de valeurs indexé par nom d'attribut
	* @return       Void
	*/
	protected final function hydrate(array $datas){
		$this->jsonFields = array();

		foreach ($datas as $key => $value){
			$temp = explode("_", $key);
			$name = "";
			foreach($temp as $val) {
				$name .= ucfirst($val);
			}

			$method = "set".ucfirst($name);
			if (method_exists($this, $method)){
				$this->$method($value);
				$this->jsonFields[] = lcfirst($name);
			}
		}
	}

	/**
	* @brief 	Extrait tous les attributs accessible via un getter, et qui sont autorisés, de l'objet pour construire un tableau
	*/
	public final function toArray(){
		$arr = array();
		foreach ($this->jsonFields as $name){
			if(isset($this->jsonException[$name]))
				continue;

			$method = "get".ucfirst($name);
			if (method_exists($this, $method))
				$arr[$name] = $this->$method();
		}

		// si aucune restriction on retourne toutes les valeurs
		if($this->jsonException == null)
			return $arr;

		// on delete les clefs interdites, cette manière de procédée est la plus optimisée par rapport à l'utilisation de clefs directement dans exception (moins agréable à l'utilisation)
		foreach($this->jsonException as $name)
			if(isset($arr[$name]))
				unset($arr[$name]);

		return $arr;
	}
}