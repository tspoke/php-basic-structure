<?php
namespace basic\core;
use PDO;
defined("_uniq_token_") or die('');

/**
* @author 	Thibaud GIOVANNETTI
*/
abstract class Model extends ConnectionHandler {
	protected $db = null;

	/** 
	* @brief 	Liste des tables liées et des paramètres de jointure
	* @example 	
				* array(
				*	"resource" => array(
				*		"type" => "LEFT", 															// défaut LEFT
				*		"on"   => array("id_base" => "id_base", "id_player" => "id_player"),		// needed
				*		"on"   => "id_base", 														// alternative
				*		"field"=> array('name')														// défaut *
				*	)
				* )
	*
	* @note 	Cette variable NE DOIT PAS être modifiable par le client !
	*/
	protected $dependencies = array();
	

	public function __construct(){
		parent::__construct();
	}

	/**
	* Permet de vérifier si les données respectent le bon format avant d'être utilisées/insérées. La fonction check() est utilisée dans add() surtout.
	* @return 	Par défaut la fonction retourne vrai.
	* @note 	Cette fonction DEVRAIT être overridée par toutes les classes qui en héritent pour réduire les erreurs lors d'insertions SQL.
	* 			Un exemple parlant est implémenté dans le model 
	*/
	public function check($datas){
		return true;
	}


	#########
	#### FIND
	#########
	
	/**
	* Retourne $limit tuples correspondant à $params
	*/
	public function find(array $params, $limit = 1){
		$data = $this->_findJoin($params, $limit);
		if(isset($data) AND isset($data[0]) AND $limit == 1)
			return $data[0];
		else if($limit == 1)
			return null;
			
		return $data;
	}
	
	/**
	* Retourne tous les tuples correspondants à $params
	*/
	public function findAll(array $params = null, $limit = 999999){
		if(!is_null($params))
			return $this->_findJoin($params, $limit);
		
		$req = $this->bdd->query("SELECT * FROM ".$this->table." LIMIT ".intval($limit));
		return $req->fetchAll();
	}

	/**
	* Retourne les tuples correspondants et leurs données jointes
	*/
	protected function _findJoin(array $params, $limit = null){
		// WHERE clause
		$where = "";
		foreach($params as $field => $value){
			if($where != "")
				$where .= " AND ";
			else
				$where = " WHERE ";
			$where .= $this->table.".".$field." = :".$field;
		}

		$join = "";
		$joinGet = "";
		// JOIN clause
		foreach($this->dependencies as $table => $datas){
			$on = "";

			//ON clause
			if(isset($datas) AND !is_array($datas)) //on a juste une valeur et non plusieurs infos
				$on = $table.".".$datas." = ".$this->table.".".$datas;
			else if(isset($datas['on']) AND is_array($datas['on'])){
				foreach($datas['on'] as $first => $second){
					if($on != "")
						$on .= " AND ";
				
					if(strpos($second, ".") === false) //pas préfixé
						$on = $table.".".$first." = ".$this->table.".".$second;
					else
						$on = $table.".".$first." = ".$second;
				}
			}
			else if(isset($datas['on']))
				$on = $table.".".$datas['on']." = ".$this->table.".".$datas['on'];
			else
				continue;

			if(isset($datas['type']))
				$join .= " ".$datas['type']." JOIN ".$table." ON ".$on;
			else
				$join .= " LEFT JOIN ".$table." ON ".$on;

			//SELECT clause
			if($joinGet != "")
				$joinGet .= ",";
				
			if(isset($datas['field'])){
				if(is_array($datas['field'])){
					foreach($datas['field'] as $get)
						$joinGet .= $table.".".$get." AS '".$table.".".$get."'";
				}
				else
					$joinGet .= $table.".".$datas['field']." AS '".$table.".".$datas['field']."'";
			}
			else
				$joinGet .= $table.".*";
		}
		if($joinGet != "")
			$joinGet .= ", ";
			
		//BUILD
		$sql = "SELECT ".$joinGet." ".$this->table.".* FROM ".$this->table.$join.$where;
		
		//LIMIT
		if(!is_null($limit) AND is_int($limit))
			$sql .= " LIMIT ".$limit;

		$req = $this->db->prepare($sql);
		
		foreach($params as $field => &$value){
			$req->bindParam(":".$field, $value, PDO::PARAM_STR);
		}

		$req->execute();
		return $req->fetchAll();
	}

	
	#########
	## DELETE
	#########

	/**
	* Supprime un tuple correspondant
	*/
	public function delete(array $params, $limit = 1){
		return $this->_delete($params, $limit);
	}
	

	/**
	* Supprime tous les tuples correspondants
	*/
	public function deleteAll(array $params){
		return $this->_delete($params);
	}
	

	/**
	* Suppression
	*/
	protected function _delete(array $params, $limit = null){
		$sql = "";
		foreach($params as $field => $value){
			if($sql != "")
				$sql .= " AND ";
			$sql .= $field." = :".$field;
		}
		
		$sql = "DELETE FROM ".$this->table." WHERE ".$sql;
		if(!is_null($limit) AND is_int($limit))
			$sql .= " LIMIT ".$limit;
			
		$req = $this->db->prepare($sql);
		
		foreach($params as $field => &$value)
			$req->bindParam(":".$field, $value, PDO::PARAM_STR);

		$req->execute();
	}
	

	#########
	##### ADD
	#########
	

	/**
	* Vérifie l'intégrité des données et ajoute un tuple
	*/
	public function add(array $params){
		if(!$this->check($params) OR count($params) == 0)
			return false;

		return $this->_add($params);
	}
	

	/**
	* Ajoute un tuple
	*/
	protected function _add(array $params){
		$sql = "";
		$values = "";
		
		foreach($params as $field => $value){
			if($sql != ""){
				$sql .= ", ";
				$values .= ", ";
			}
			$sql .= $field;
			$values .= ":".$field;
		}
		
		$sql = "INSERT INTO ".$this->table."(".$sql.") VALUES (".$values.")";
		$req = $this->db->prepare($sql);
		
		foreach($params as $field => &$value){
			$req->bindParam(":".$field, $value, PDO::PARAM_STR);
		}

		$req->execute();

		return $this->db->lastInsertId();
	}
	


	#########
	## UPDATE
	#########

	/**
	* met à jour un tuple selectionné avec where avec les données dans params
	* @param 	$where 		Données pour sélectionner un tuple spécifique (eg: array('id' => 1))
	* @param 	$params 	Données à modifier (eg: array("value" => 10))
	*/
	public function update(array $where, array $params){
		if(!$this->check($params) OR count($params) == 0)
			return false;

		return $this->_update($where, $params);
	}

	protected function _update(array $where, array $params){
		$sql = "";
		$values = "";
		$whereSQL = "";

		// find where
		foreach($where as $field => $value){
			if($whereSQL != "")
				$whereSQL .= " AND ";
			$whereSQL .= $this->table.".".$field." = :".$field;
		}

		// values
		foreach($params as $field => $value){
			if($values != "")
				$values .= ", ";
			$values .= "`".$field."` = :VAL_".$field; // préfixé pour éviter les collisions de noms
		}

		//BUILD
		$sql = "UPDATE ".$this->table." SET ".$values." WHERE ".$whereSQL;

		$req = $this->db->prepare($sql);
		foreach($where as $field => &$value)
			$req->bindParam(":".$field, $value, PDO::PARAM_STR);
		foreach($params as $field => &$value)
			$req->bindParam(":VAL_".$field, $value, PDO::PARAM_STR);
		$req->execute();

		return $this->db->rowCount();
	}
}