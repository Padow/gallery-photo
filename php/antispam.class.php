<?php  
Class Antispam extends Connexion{

		public function __construct(){
			$this->_connexion = parent::__construct();
		}

		public function antiSpam($ip){
			$interval = $this->getInterval($ip);
			return $interval>20;
		}

		public function timeLeft($ip){
			$interval = $this->getInterval($ip);
			return 21-$interval;
		}

		public function getInterval($ip){
			$sql = $this->_connexion->prepare("SELECT MAX(date) AS lastpost FROM comments WHERE ip = :ip");
			$sql-> bindParam('ip', $ip, PDO::PARAM_STR);
			$sql-> execute();
			$rows = $sql->fetchAll(PDO::FETCH_ASSOC);

			$timeFirst  = strtotime($rows[0]['lastpost']);
			$date = date("Y-m-d H:i:s");	
			$timeSecond = strtotime($date);
			$interval = $timeSecond - $timeFirst;

			return $interval;
		}


}