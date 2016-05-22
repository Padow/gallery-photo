<?php  
 Class Param {

 	public function getJson(){
 		$path = "config/param.json";
 		if (file_exists($path)) {
 			$array = json_decode(file_get_contents($path));
			return $array;
 		}
 	}

 	public function setPageTitle(){
 		if ($this->getJson()){
 			$title = $this->getJson()->{'header'}->{'pagename'};
 		}
 		if ((isset($title)) && ($title !=""))
 			echo $title;
 		else
 			echo 'Galerie photo';
 	}

 	public function setContentTitle(){
 		if ($this->getJson()){
 			$content = $this->getJson()->{'content'}->{'title'};
 		}
 		if ((isset($content)) && ($content !=""))
 			echo '<h1>'.$content.'</h1>';
 		else
 			echo '<h1>Galeries</h1>';
 	}

	public function setContentSubTitle(){
	 if ($this->getJson()){
		 $content = $this->getJson()->{'content'}->{'subtitle'};
	 }
	 if ((isset($content)) && ($content !=""))
		 echo '<p>'.$content.'</p>';
	 else
		 echo '<p>Subtitle</p>';
	}

 	public function setFooter(){
 		if ($this->getJson()){
 			$contact = $this->getJson()->{'footer'}->{'contact'};
 		}
 		if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $contact))
 			echo '<a href="contact.php" >Contact</a>';
 		echo '<script type="text/javascript" src="js/analytics.js"></script>';
 	}

 	public function getEmail(){
 		$contact = $this->getJson()->{'footer'}->{'contact'};
 		return $contact;
 	}


 }
