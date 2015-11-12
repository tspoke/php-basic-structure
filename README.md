<h1>php-basic-structure</h1>
<p>
	Un framework léger pour accélérer vos développements sur des petits projets.
</p>


<h2>Présentation</h2>
<p>
L'objectif principal de ce projet est de se distinguer des frameworks actuels en ne fournissant qu'une base minimale pour
commencer à développer.<br />

Ce framework permet de simplifier les petits développements de sites web en proposant une architecture MVC2 et quelques fonctionnalités de base.
Il peut également servir de base pour de plus gros développements.<br />
<br />
</p>

<h2>Remarques</h2>
<p>J'ai constaté que beaucoup de développeur amateurs/débutants ne se penchaient pas sur les frameworks en général. Cela souvent à cause de la complexité et le temps de mise en route de tels outils.<br />
Utiliser un petit framework tel quel celui-ci permet de tester une nouvelle architecture et cela sans devoir lire des kilomètres de documentation. Enfin, cela est une bonne base pour ensuite migrer sur de plus gros environnements tel que Symfony.<br /><br />

Ce framework est codé sans prétention, on peut faire mieux, beaucoup mieux même. D'ailleurs au départ ce n'était qu'un morceau réutilisable que j'utilisais pour mes développements, il 
n'était pas voué à être diffusé. Je l'ajoute à mon github au cas où des gens s'y intéresseraient !</p>

<h2>Principales fonctionnalités</h2>
<p>Peu de fonctions, mais un framework léger qui vous permet de changer facilement le code pour l'adapter.</p>
<ul>
	<li>Architecture MVC2</li>
	<li>Réécriture d'URL</li>
	<li>Classe d'abstraction des modèles et des requêtes simples</li>
	<li>Outils d'inclusion de CSS et de JS</li>
	<li>Autoload de classes</li>
	<li>Utilisation de PDO</li>
</ul>

<h2>Installation</h2>
<ol>
	<li>Téléchargez le dossier complet</li>
	<li>Copiez le contenu dans votre dossier de développement.</li>
	<li>Si le module mod_rewrite n'est pas activé sur Apache, activez-le.</li>
	<li>core/config.php : Modifiez vos informations de connexion à la base de données.</li>
	<li>core/init.php : Si besoin modifiez l'état du projet (production ou non) et les liens des constantes</li>
	<li>C'est terminé. Des exemples sont programmés dans controllers/example.php</li>
</ol>

<h3>Choix techniques</h3>
<p>
J'ai fait le choix de programmer en Objet les modèles et les controlleurs.<br /><strong>Attention cependant</strong>, les modèles sont uniquement des wrappers des tables de la base de données.
Cela signifie qu'on ne manipule aucun objet lorsqu'on parle des données en provenance de la base, ce sont des tableaux PHP.<br /> 
Vous pouvez très bien changer le code et implémenter un système de création d'objets si vous avez besoin d'un contrôle des données, voire, à défaut, d'utiliser des StdClass.<br />
Pour ma part, les tableaux c'est puissant et suffisant.<br /><br />
</p>
<p>La classe <strong>Handler</strong> est un proxy pour différentes fonctionnalités/sous-classes (qu'il faut écrire). 
Je vous invite à réécrire cette classe et à utiliser votre propre gestionnaire d'erreurs et d'exceptions.</p>

<h3>Utilisation rapide</h3>
<h4>Créer une page</h4>
<p>
	Pour créer une page nommée contact :
	<ol>
		<li>Vue : Créer un fichier dans views/ nommée contact.php</li>
		<li>Contrôleur : Créer un fichier dans controllers/ nommé contact.php</li>
		<li>Dans le contrôleur, créer une classe <i>ContactController</i> qui hérite de <i>Controller</i></li>
		<li>Déclarer une méthode publique <i>index()</i> et copier ce code dedans : <i>$this->view = "contact";</i></li>
		<li>Terminé ! Accéder à l'url : votresite.com/contact</li>
	</ol>
</p>

```php
// Exemple complet d'un controleur
<?php
class ContactController extends Controller {
	
	// méthode par défaut qui sera appelée
	public function index(){
		$this->view = "contact";
	}
}
?>
```

<h4>Utiliser du CSS et du JS</h4>
<p>Par défaut votre page utilise reset.css et common.css. Ces deux classes permettent dans l'ordre de faire un reset CSS puis d'ajouter quelques classes génériques qui peuvent vous être utiles.
Si vous souhaitez ajouter du code CSS global à toute votre application vous pouvez soit le mettre dans common.css soit créer un fichier css et l'ajouter dans le HEAD avec un LINK.<br/><br />

Pour ajouter du CSS/JS spécifique à une page précise, vous pouvez le faire dans le contrôleur PHP avec la méthode statique de CSS/JS :<br />
</p>
```php
	Includer::add('css', 'fichier'); // ajoute un fichier CSS
	Includer::add('css', 'dossier/'); // ajoute un dossier (tous les CSS contenues)
	Includer::add('js', 'fichier'); // ajoute un fichier JS
	Includer::add('js', 'dossier/'); // ajoute un dossier (tous les JS contenus)
```

<strong>Tous les fichiers inclus par la classe Includer doivent être situés dans static/EXTENSION (ex: static/js, static/css, ...)</strong><br />
<strong>Bon à savoir :</strong> n'importe quel type de fichier peut être inclu de cette manière. Il faut qu'il soit stocké dans un dossier portant le nom de l'extension que vous voulez utiliser.<br />
</p>

<h4>Passer des variables dans la vue</h4>
<p>Les variables sont passées comme tableaux et elles sont accessibles via le nom de leur clef depuis la vue.</p>
```php
	// dans le contrôleur
	$data['bonjour'] = "hello world";
	$data['variable'] = array("Une autre variable", "sous forme de tableau");
	$this->set($data);
	
	// dans la vue
	echo $bonjour; // affiche hello world
	echo $variable[0]; // affiche 'Une autre variable'
```

<h4>Utiliser les modèles dans un contrôleur</h4>
<p>Pour récupérer des données de la base de données il faut demander à un Modèle de vous les récupérer. Pour utiliser un modèle pas besoin de l'instancier, une méthode s'en charge.</p>

```php
// dans un contrôleur pour charger un modèle User & News
$this->loadModel('User');
$this->loadModel('News');

// utilisation : on récupère des données
$user = $this->User->lastUserRegistered();
$article = $this->News->last();
```

<h4>Modèles et classe abstraite</h4>
<p>Quelques exemples d'utilisation de la classe Model.php qui doit être étendue par les modèles de votre application. Ces méthodes permettent surtout de simplifier les requêtes SQL de base. Libre à vous de les utiliser.</p>

```php
// dans un contrôleur on charge le model
$this->loadModel('User');

// sélection simple
$user = $this->User->find(array('id' => 1)); // sql = SELECT * FROM user WHERE id = 1 LIMIT 1

// sélection complexe : le deuxième paramètre (5) est optionnel, il permet de limiter le nombre de tuples
$users = $this->User->findAll(array('age' => 10, 'taille' => '150'), 5); // SELECT * FROM user WHERE age = 10 AND taille = 150 LIMIT 5

// suppression
$this->User->delete(array('id' => 1)); // DELETE FROM user WHERE id = 1

// ajout
$this->User->add(array('age' => 25, 'taille' => 176, 'nom' => 'Spoke')); // INSERT INTO user (age, taille, nom) VALUES (25, 176, 'Spoke')

// modification
// le premier paramètre est le(s) tuple(s) que vous voulez modifier, le deuxième les valeurs à changer
$this->User->update(array("id" => 1, "my_key" => 'abc'), array("my_key" => 50, "kikoo" => "abcd"));
```

<p>
Il y a peu de méthodes dans Model.php. C'est volontaire et cela fait parti du concept de ce projet de laisser au programmeur le soin de coder les 
requêtes qui n'entrent pas le champ d'utilisation de Model.php <br />
Le tableau de paramètre est uniquement traité comme <b>AND</b> pour la requête, pas de <i>OR</i>.
Vous pouvez automatiser une jointure (systématique) en modifiant la variable <b>$this->dependencies</b> d'un modèle. <br />
<strong>Toutes les requêtes de Model.php sont des requêtes préparées ! </strong>
</p>

<h4>Requêtes SQL personnalisées</h4>
<p>
	Toutes les classes qui héritent de Model possèdent un pointeur sur la connexion vers la base de données (voir TODO sur la classe core/Connection).<br />
	Vous pouvez donc effectuer facilement une requête dans n'importe quel modèle.
</p>

```php
// dans une classe héritant de model
class News extends Model {
	// $start et $end en INTEGER
	public function getNewsBetweenTwoDates($start, $end){
		// $this->db est le pointeur vers la connexion
		$req = $this->db->prepare("SELECT * FROM news WHERE date_publication BETWEEN :start AND :end");
		$req->bindParam('start', $start, PDO::PARAM_INT); // utilisation standard de PDO
		$req->bindParam('end', $end, PDO::PARAM_INT);
		$req->execute();

		return $req->fetchAll();
	}

	// Autre exemple avec une query simple 
	// Remarque 1 : Je vous conseille de ne jamais passer des variables dans les query simple mais d'utiliser les requêtes préparées pour le faire.
	// Remarque 2 : C'est juste un exemple car Model contient déjà une méthode pour ce genre de requête => $this->find(array('id' => $id))
	public function getNews($id){
		$req = $this->db->query("SELECT * FROM news WHERE id = ".intval($id));
		return $req->fetchAll();
	}
}
```

<hr></hr>
<h1>About</h1>
A small and lightweight PHP framework for fast development.
