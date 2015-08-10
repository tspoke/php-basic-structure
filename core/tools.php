<?php

/**
* @class	Tools
* @brief	Ensemble d'outils communs
*/
class Tools {
	/**
	* @brief	Sécurise en UTF-8 une chaîne de caractères en vue de son affichage HTML
	* @param	String		$string		Chaîne de caractères à sécuriser
	* @return 	String		Chaîne sécurisée
	*/
	public static function secureHTML($string)
	{
		$newstring = htmlspecialchars($string, ENT_COMPAT, "UTF-8");
		return $newstring;
	}
	
	/**
	* @brief	Sécurise en UTF-8 une chaîne de caractères ou un nombre
	* @param	$string					Chaîne de caractères à sécuriser ou nombre
	* @param	boolean		$forced		Si un nombre est renseigné, on peut le forcer à être validé comme une String et non comme un @a int (float notamment)
	* @retval 	String		Chaîne sécurisée
	* @retval 	int			Chaîne sécurisée
	*/
	public static function secure($string, $forced = false)
	{
		$newstring = $string;
		if(ctype_digit($string) AND $forced == false)
		{
			$newstring = intval($string);
		}
		else
		{
			$newstring = addcslashes($string, '%_');
			$newstring = htmlspecialchars($newstring, ENT_COMPAT, "UTF-8");
			$newstring = nl2br($newstring);
		}

		return $newstring;
	}
	
	/**
	* @brief	Redirige vers l'url indiquée. Cette url n'a de portée que dans la structure actuelle MVC.
	* @param	String		$url		URL de destination
	* @param	boolean		$header		Demander une redirection par header HTTP
	* @param	Array		$getparams	Paramètres GET supplémentaires à lier à l'url
	* @return	Void
	* @note		Par défaut la redirection est une redirection HTML suivie d'un die()
	*/
	public static function redirect($url, $header = false, $getparams = array())
	{
		if(isset($getparams)){
			foreach($getparams as $key => $value){
				$url .= "&".Tools::securiser($key)."=".Tools::securiser($value);
			}
		}
		if(!$header)
			die('<meta http-equiv="refresh" content="0;URL=index.php?p='.$url.'">');
		else
			header("Location: ".URL.$url);
	}
	
	/**
	* @brief	Redirige vers l'url indiquée (peut être externe)
	* @param	String		$url		URL de destination
	* @param	boolean		$header		Demander une redirection par header HTTP
	* @param	Array		$getparams	Paramètres GET supplémentaires à lier à l'url
	* @return	Void
	* @note		Par défaut la redirection est une redirection HTML suivie d'un die()
	*/
	public static function redirectTo($url, $header = false, $getparams = array(), $_blank = false)
	{
		if(isset($getparams)){
			foreach($getparams as $key => $value){
				$url .= "&".Tools::securiser($key)."=".Tools::securiser($value);
			}
		}
		if(!$header)
			die('<meta http-equiv="refresh" content="0;URL='.$url.'">');
		else
			header("Location: ".URL.$url);
	}
	
	/**
	* @brief	Retourne une date PHP depuis une date SQL
	* @param	$value		Date SQL
	* @return	Date		Date PHP
	*/
	public static function sqlToDate($value)
	{
		return DateTime::createFromFormat('d-m-Y H:i:s', strtotime($value));
	}
	
	/**
	* @brief	Retourne la date courante selon la timezone UTC
	* @return	Date	Date courante UTC
	*/
	public static function currentDate()
	{
		date_default_timezone_set('UTC');
		return date("Y-m-d H:i:s");
	}
	
	/**
	* @brief	Formate une heure en ajoutant un 0 devant si celle-ci est inférieur à 10
	* @param	int		$value		Heure
	* @return	String				Heure formatée en String
	*/
	public static function formatTime($value)
	{
		if($value < 10)
			return "0".$value;
		return $value;
	}
	
	/**
	* @brief	Retourne la classe de l'objet 
	* @param	Object		$objet		Objet 
	* @return	String		Nom de la classe de l'objet
	*/
	public static function getClass($objet){
		if(is_object($objet))
			return get_class($objet);
		return false;
	}
	
	/**
	* @brief	Retourne la classe parente de l'objet 
	* @param	Object		$objet		Objet 
	* @return	String		Nom de la classe parente de l'objet
	*/
	public static function getParentClass($objet){
		if(is_object($objet))
			return get_parent_class($objet);
		return false;
	}
	
	/**
	* @brief	Génère un hash unique de 40 caractères
	* @param	String		$pass		Mot de passe de l'inscription ou n'importe quel grain de sel (salt)
	* @return	String		Chaîne unique hashé en 40 caractères
	*/
	public static function hash($pass)
	{
		return sha1("jzaoek,5*9*sd6".$pass."zakl%^diç_Jkdk");
	}
	
	/**
	* @brief	Génère une string unique de 40 caractères
	* @param	String		$email		Email d'inscription ou n'importe quelle chaîne de caractères 
	* @return	String		Chaîne unique de 40 caractère
	*/
	public static function generateSession($email)
	{
		return sha1(uniqid().$email);
	}
	
	/**
	* @brief	Vérifie si un password est correct
	* @param	String		$pass		Mot de passe 
	* @return	boolean		@b TRUE s'il est correct
	*/
	public static function checkPassword($pass)
	{
		if(strlen($pass) >= 5 && strlen(trim($pass)) <= 20)
			return true;
		else	
			return false;
	}
	
	/**
	* @brief	Vérifie si un nom est correct, notamment l'absence de chiffres
	* @param	String		$name		Nom à vérifier
	* @return	boolean		@b TRUE s'il est correct
	*/
	public static function checkName ($name)
	{
		if(preg_match("#[a-zA-z éèêëàâä'-]+#i", $name) and strlen($name)>=2 )
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si un code postal est correct
	* @param	String		$zip		Code postal à vérifier
	* @return	boolean		@b TRUE s'il est correct
	*/
	public static function checkZip($zip)
	{
		if(preg_match("#^[0-9]{5}$#", $zip)and $zip!="")
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si un nom de ville est correct
	* @param	String		$city		Nom de la ville à vérifier
	* @return	boolean		@b TRUE s'il est correct
	*/
	public static function checkCity($city)
	{
		if(preg_match("#[a-z éèêëàâä'-]{2,}#i", $city) and strlen($city)>=2 )
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si une adresse est correcte
	* @param	String		$address		Adresse à vérifier
	* @return	boolean		@b TRUE si elle est correcte
	*/
	public static function checkAddress($address)
	{
		if(preg_match("#^[a-zA-Z0-9 éèêëàâä]+$#", $address) AND strlen($address) >= 2)
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si un numéro de téléphone respecte le bon format
	* @param	String		$phone		Numéro de téléphone à vérifier
	* @return	boolean		@b TRUE s'il est correct
	*/
	public static function checkPhone($phone)
	{
		if(preg_match("#^[0]{1}[0-79]{1}[0-9]{8}$#", $phone) AND $phone =! "")
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si une date de naissance est correcte
	* @param	String		$birthday		Date d'anniversaire à vérifier
	* @return	boolean		@b TRUE si elle est correcte
	*/
	public static function checkBirthday($birthday)
	{
		//le type date affiche la date AAAA-MM-JJ. A voir si - est pris en compte
		if(preg_match("#^[1-2]{1}[09]{1}[0-9]{2}[-]?[0-1]{1}[0-9]{1}[-]?[0-3]{1}[0-9]{1}$#", $birthday))
			return true;
		else
			return false;
	}

	/**
	* @brief	Vérifie si un email est valide et s'il n'appartient pas à une liste d'emails jetables
	* @param	String		$email		Email à vérifier
	* @return	boolean		@b TRUE s'il est valide
	* @see 		Tools::verifierEmailJetable()
	*/
	public static function checkEmail($email)
	{
		if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $email) && self::verifierEmailJetable($email) && strlen(trim($email)) <= 100)
			return true;
		return false;
	}
	
	/**
	* @brief	Vérifie si un ID est au bon format
	* @param	int		$id		ID à vérifier
	* @return	boolean		@b TRUE s'il est au bon format
	*/
	public static function checkId($id)
	{
		if (is_int($id))
			return true;
		else
			return false;
	}
	
	/**
	* @brief	Vérifie si une date respecte le format @a AAAA-MM-JJ
	* @param	String		$date		Date à vérifier
	* @return	boolean		@b TRUE si valide
	*/
	public static function checkDate($date)
	{
		if(preg_match("#^[1-2]{1}[0-9]{1}[0-9]{2}[-]{1}[0-1]{1}[0-9]{1}[-]{1}[0-3]{1}[0-9]{1}$#", $date))
			return true;
		else 
			return false;
	}
	
	/**
	* @brief	Vérifie si une heure respecte le format @a HH:MM
	* @param	String		$time		Heure à vérifier
	* @return	boolean		@b TRUE si valide
	*/
	public static function checkTime($time)
	{
		if(preg_match("#^[0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$#", $time))
			return true;
		else 
			return false;
	}
	
	/**
	* @brief	Vérifie si une heure est bien comprise entre 0 et 24h et qu'elle est bien au format numérique
	* @param	int			$hour		Heure à vérifier
	* @return	boolean		@b TRUE si valide
	*/
	public static function checkHour($hour)
	{
		if(is_numeric($hour))
		{ 
			if ($hour >= 0 AND $hour <= 24)
				return true;
			else
				return false;
		}
		else
			return(false);
	}

	/**
	* @brief	Vérifie si les minutes d'une heures sont bien comprises entre 0 et 59 et qu'elles sont bien au format numérique
	* @param	int			$minute		Minutes à vérifier
	* @return	boolean		@b TRUE si valide
	*/
	public static function checkMinute($minute)
	{
		if(is_numeric($minute))
		{
			if ($minute >= 0 and $minute <= 59)
				return true;
			else
				return false;
		}
		else
			return(false);
	}
	
	/**
	* @brief	Vérifie si le sexe est valide
	* @param	String		$sexe		Sexe à vérifier
	* @return	boolean		@b TRUE si @a homme ou @a femme
	*/
	public static function checkSexe($sexe){
		if($sexe == "homme" OR $sexe == "femme")
			return true;
		return false;
	}
	
	/**
	* @brief	Transforme une date (aaaa-mm-dd) en age (ex: 25)
	* @param	String		$date		Age à transformer
	* @return	Array		Tableau indexé à y, m et d
	*/
	public static function getAge($date){
		$temp = explode("-", $date);
		return array("y" => date('Y', time()) - $temp[0], "m" => $temp[1], "d" => $temp[2]);
	}
	
	/**
	* @brief	Retourne un chiffre sous un format d'affichage spécifié
	* @param	float		$nbr		Chiffre à formater
	* @return	String		Chiffre formaté
	*/
	public static function number($nbr)
	{
		return number_format($nbr, 0, ',', '.');
	}
	
	/**
	* @brief	Vérifie si l'email est un email jetable
	* @param	String		$email		Email à vérifier
	* @return	boolean		@b TRUE si l'email est valide et non jetable
	*/
	public static function verifierEmailJetable($email)
	{
		$domaine_extrait = preg_replace('!^[a-z0-9._-]+@(.+)$!', '$1', $email);
		$domaine_bloques = array ('meltmail.com', 'jetable.com', 'jetable.org', 'jetable.net',
		'jetable.fr.nf', 'filzmail.com', 'ephemail.net', 'trashmail.net', 'spamcorptastic.com',
		'yopmail.com', 'yopmail.fr', 'yopmail.net', 'yopweb.com', 'spamgourmet.com', 'haltospam.com',
		'iximail.com', 'temporaryinbox.com', 'mailincubator.com', 'mailbidon.com', 'cool.fr.nf',
		'courriel.fr.nf', 'moncourrier.fr.nf', 'monemail.fr.nf' , 'monmail.fr.nf', 'kleemail.com',
		'xblogz.org', 'link2mail.net', 'spam.la', 'spam.su', 'mailinator.com', 'mailinator2.com',
		'sogetthis.com', 'mailin8r.com', 'mailinator.net', 'spamherelots.com', 'thisisnotmyrealemail.com',
		'wh4f.org', 'spamfree24.org', 'spamfree24.com', 'spamfree24.eu', 'spamfree24.net',
		'spamfree24.info', 'spamfree24.de', 'trashymail.com', 'mytrashmail.com', 'mt2009.com',
		'pourri.fr', 'dupemail.com', 'email-jetable.com', 'correo.blogos.net', 'pookmail.com', 
		'1-12.nl', '127-0-0-1.be', '3v1l0.com', 'aliraheem.com', 'aliscomputer.info',
		'bankofuganda.dontassrape.us', 'black-arm.cn', 'black-leg.cn', 'black-tattoo.cn', 
		'blacktattoo.cn', 'bonaire.in', 'casema.org', 'churchofscientology.org.uk', 'copcars.us',
		'definatelynotaspamtrap.com', 'djlol.dk', 'edwinserver.se', 'fuzzy.weasel.net',
		'har2009.cn', 'hermesonline.dk', 'm.nonumberno.name', 'jpshop.ru', 'junk-yard.be',
		'junk-yard.eu', 'laughingman.ath.cx', 'linux.co.in', 'lolinternets.com', 'madcrazydesigns.com',
		'maleinhandsmint.czarkahan.net', 'newkurdistan.com', 'nigerianscam.dontassrape.us',
		'ninjas.dontassrape.us', 'no-spam.cn', 'omicron.token.ro', 'pengodam.biz',
		'pirates.dontassrape.us', 'pirazine.se', 's.blackhat.lt', 'sales.bot.nu',
		'sales.net-freaks.com', 'sendmeshit.com', 'slarvig.se', 'slaskpost.cn', 'slaskpost.se',
		'slop.jerkface.net', 'slops.lazypeople.co.uk', 'slops.quadrath.com', 'slopsbox.com',
		'slopsbox.net', 'slopsbox.org', 'slopsbox.se', 'slopsbox.slarvig.se', 'slopsbox.spammesenseless.dk',
		'slopsbox.stivestoddere.dk', 'solidblacktattoo.cn', 'spam.dontassrape.us', 'spam.h0lger.de',
		'spam.hack.se', 'spam.mafia-server.net', 'spam.tagnard.net', 'spam.w00ttech.com',
		'spamout.jassi.info', 'thegaybay.com', 'trash-can.eu', 'tyros2.cn', 'vuilnisbelt.cn',
		'watertaxi.net', 'west.metaverseaudio.com', 'your.gay.cat', 'ziggo.ws', 'zynd.com',
		'spailbox', 'spailbox', 'realcambio.com', 'watchnode.uni.cc', 'gimme.wa.rez.se',
		'pyramidspel.com', 'slopsbox.osocial.nu', 'freenet6.de', 'dodgit.com', 'guerrillamail.org',
		'kaspop.com', 'farifluset.mailexpire.com', 'mailnull.com', 'nospamfor.us', 'nospam4.us',
		'spambox.us', 'TempEMail.net', 'tempinbox.com', 'get2mail.fr');
		
		if (in_array($domaine_extrait, $domaine_bloques))
			return false;
		else
			return true;
	}
}
?>