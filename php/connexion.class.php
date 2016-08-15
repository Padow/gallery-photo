<?php
	$path = __DIR__;
	$path = substr($path, 0, -3);
	$path .= "config/sql.json";
	define("CONFIG", $path);
	if (file_exists($path)) {
		$array = json_decode(file_get_contents($path));
		define("DBDRIVER", $array->{'sql'}->{'driver'});
		define("DBHOST", $array->{'sql'}->{'host'});
		define("DBUSER", $array->{'sql'}->{'user'});
		define("DBPASSWORD", $array->{'sql'}->{'password'});
		define("DBNAME", $array->{'sql'}->{'database'});
	}
	class Connexion{
		protected $_connexion;
		public function __construct(){
			try
			{
			    @$connexion = $this->connexion();
			    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $this->_connexion = $connexion;
			}
			catch(Exception $e)
			{
				echo 'Erreur : '.$e->getMessage().'<br />';
				echo 'N° : '.$e->getCode();
				echo '<div class="alert alert-danger"><strong>Warning!</strong> Database is not configured please watch : "'.CONFIG.'"</div>';
				die();
			}
			return $this->_connexion;
		}

		private function connexion(){
			switch (DBDRIVER) {
				case 'sqlite3':
					$dbpath = substr(__DIR__, 0, -3);
					return new PDO('sqlite:'.$dbpath.DBNAME.'.db');
					break;
				default:
					return new PDO(''.DBDRIVER.':host='.DBHOST.';dbname='.DBNAME.'', DBUSER, DBPASSWORD);
			}
		}
	}
