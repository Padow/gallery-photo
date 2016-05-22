<?php  

	Class Gallery extends Connexion{
		public function __construct(){
			$this->_connexion = parent::__construct();
			$this->displayGalleries();
		}

		private function displayGalleries(){
			$sql = $this->_connexion->prepare("SELECT galleries.id, galleries.thumb, galleries.name,  MIN(pictures.thumb) AS thumbdef FROM galleries JOIN pictures ON galleries.id = pictures.gallery group by galleries.id ORDER BY galleries.name");
			$sql-> execute();
			$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
			$cpt = 1;
			foreach ($rows as $value) {
				$thumb = empty($value['thumb'])?$value['thumbdef']:$value['thumb'];
				echo '<a href="gallery.php?gal='.$value['id'].'"><div id="grid'.$cpt.'" class="grid"><img id="'.$cpt.'"  src="'.$thumb.'" class="img" alt="Responsive image"><div id="gallery_grid_title'.$cpt.'" class="gallery_grid_title"><p class="galtitle">'.$value['name'].'<p></div></div></a>';
				$cpt++;
			}
		}
	}
