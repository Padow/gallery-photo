<?php  

	Class Gallery extends Connexion{

		public function __construct(){
			$this->_connexion = parent::__construct();
			$dir = 'photos';

			if (!is_dir($dir))
				mkdir($dir);
			$gallery_list = array();
			$gallery = scandir($dir);
			foreach ($gallery as $value) {
				if ($value != '.' && $value !='..') {
					$gallery_list[$value] = $dir.'/'.$value;				
				}
			}
			$this->check_galleries($gallery_list);
			foreach ($gallery_list as $key => $value) {
				$gallery_content = scandir($value);
				// if gallery folder not contains `thumbs` folder
				if($this->isThumbsExist($gallery_content)){
					if ($this->metadata($gallery_content)) { // if gallery folder not contains metadata.txt
						$this->newGalleryTxt($value);
					}elseif ($this->metadataJson($gallery_content)) { // if gallery folder not contains metadata.json
						$this->newGalleryJson($value);
					}else{
						$this->newGallery($value);
					}
				}
				$this->checkPic($value, $key);
			}
			
			$this->displayGalleries();
		}

		/**
		*	return all images from a folder
		*
		*	@param String (path to folder)
		*	@return Array() (content of the folder)
		*	
		*/
		public function getAllPics($dir){
			$gallery_content = scandir($dir);
			$regex = "/\.(bmp|tiff|png|gif|jpe?g)$/";
			$gallery_pictures = array();
			foreach ($gallery_content as $value) {
				if (preg_match($regex, $value)) {
					$gallery_pictures[] = $value;
				}
			}
			return $gallery_pictures;
		}

		/**
		*	creat thumbnail from pics
		*
		*	@param String (name of the pic, path to the folder pic, path to the thumb folder)
		*	
		*/
		public function createThumbnail($filename, $path_to_image_directory, $path_to_thumbs_directory ) {

			$final_width_of_image = 300;

			if(preg_match('/[.](jpg)$/', $filename)) {
				$im = imagecreatefromjpeg($path_to_image_directory . $filename);
			} else if (preg_match('/[.](gif)$/', $filename)) {
				$im = imagecreatefromgif($path_to_image_directory . $filename);
			} else if (preg_match('/[.](png)$/', $filename)) {
				$im = imagecreatefrompng($path_to_image_directory . $filename);
			}
 
			$ox = imagesx($im);
			$oy = imagesy($im);

			$nx = $final_width_of_image;
			$ny = floor($oy * ($final_width_of_image / $ox));
			$nm = imagecreatetruecolor($nx, $ny);

			imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

			if(!file_exists($path_to_thumbs_directory)) {
				if(!mkdir($path_to_thumbs_directory)) {
					die("There was a problem. Please try again!");
				}
			}

			imagejpeg($nm, $path_to_thumbs_directory .'/'. $filename);
		}

		/**
		*	Check if gallery folder in the BDD exist in photos directory
		*	if not erase it
		*	@param Array() (content of "photos" folder)
		*	
		*/
		public function check_galleries($gallery_list){
			$sql = $this->_connexion->prepare("SELECT folder FROM galleries");
			$sql-> execute();
			$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as $key => $value) {
				$folder = 'photos/'.$value['folder'];
				if (!in_array($folder, $gallery_list)) {
					$this->deleteGallery($value['folder']);
				}
			}
		}

		public function deleteGallery($folder_name){
			$sql_get_id = $this->_connexion->prepare("SELECT id FROM galleries WHERE folder = :folder");
			$sql_get_id-> bindParam('folder', $folder_name, PDO::PARAM_STR);
			$sql_get_id-> execute();
			$rows = $sql_get_id->fetchAll(PDO::FETCH_ASSOC);
			$id = $rows[0]['id'];

			$sql_del_com = $this->_connexion->prepare("DELETE FROM comments WHERE gallery = :id");
			$sql_del_com-> bindParam('id', $id, PDO::PARAM_INT);
			$sql_del_com-> execute();

			$sql_del_pic = $this->_connexion->prepare("DELETE FROM pictures WHERE gallery = :id");
			$sql_del_pic-> bindParam('id', $id, PDO::PARAM_INT);
			$sql_del_pic-> execute();

			$sql_del_gal = $this->_connexion->prepare("DELETE FROM galleries WHERE folder = :folder");
			$sql_del_gal-> bindParam('folder', $folder_name, PDO::PARAM_STR);
			$sql_del_gal-> execute();
		}

		public function insertPics($id, $pic_title, $pic_subtitle, $link, $thumb){
			$sql = $this->_connexion->prepare("INSERT INTO pictures (gallery, name, info, link, thumb) values(:gallery, :name, :info, :link, :thumb)");
			$sql-> bindParam('gallery', $id, PDO::PARAM_INT);
			$sql-> bindParam('name', $pic_title, PDO::PARAM_STR);
			$sql-> bindParam('info', $pic_subtitle, PDO::PARAM_STR);		
			$sql-> bindParam('link', $link, PDO::PARAM_STR);		
			$sql-> bindParam('thumb', $thumb, PDO::PARAM_STR);		
			$sql-> execute();
		}

		public function insertGallery($folder_name, $gallery_title, $gallery_subtitle){
			$sql = $this->_connexion->prepare("INSERT INTO galleries (folder, name, subtitle) values(:folder, :name, :subtitle)");
			$sql-> bindParam('folder', $folder_name, PDO::PARAM_STR);
			$sql-> bindParam('name', $gallery_title, PDO::PARAM_STR);
			$sql-> bindParam('subtitle', $gallery_subtitle, PDO::PARAM_STR);			
			$sql-> execute();
		}

		public function getGalleryId($folder_name){
			$sql2 = $this->_connexion->prepare("SELECT id FROM galleries where folder = :folder");
			$sql2-> bindParam('folder', $folder_name, PDO::PARAM_STR);
			$sql2-> execute();
			$rows = $sql2->fetchAll(PDO::FETCH_ASSOC);
			$id = $rows[0]['id'];
			return $id;
		}	

		public function displayGalleries(){
			$sql = $this->_connexion->prepare("SELECT galleries.id, galleries.name,  MIN(pictures.thumb) AS thumb FROM galleries JOIN pictures ON galleries.id = pictures.gallery group by galleries.id ORDER BY galleries.name");
			$sql-> execute();
			$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
			$cpt = 1;
			foreach ($rows as $value) {
				echo '<a href="gallery.php?gal='.$value['id'].'"><div id="grid'.$cpt.'" class="grid" onMouseover="bounce(this.id)"><img id="'.$cpt.'"  src="'.$value['thumb'].'" class="img" alt="Responsive image"><div id="gallery_grid_title'.$cpt.'" class="gallery_grid_title"><p class="galtitle">'.$value['name'].'<p></div></div></a>';
				$cpt++;
			}
			
		}

		/**
			add gallery without metadata
		*/

		public function isThumbsExist($gallery_content){
			return !in_array('thumbs', $gallery_content);	
		}

		public function newGallery($file){
			$folder_name = preg_split("/\//", $file);
			$folder_name = $folder_name[1];
			$title = $folder_name;
			$subtitle = "";
			$this->insertGallery($folder_name, $title, $subtitle);
			$id = $this->getGalleryId($folder_name);
			$this->addPictures($file, $id);
		}

		public function addPictures($dir, $id){
			$gallery_pictures = $this->getAllPics($dir);

			$pics_to_add = array();
			foreach ($gallery_pictures as $value) {
				$pics_to_add[$value]['title'] = htmlspecialchars($value, ENT_QUOTES);
				$pics_to_add[$value]['subtitle'] = "";

			}

			foreach ($pics_to_add as $key => $value) {
				$link = $dir.'/'.$key;
				$thumb_dir = $dir.'/thumbs';
				$thumb = $thumb_dir.'/'.$key;
				$this->createThumbnail($key, $dir.'/', $thumb_dir );
				$this->insertPics($id, $value['title'], $value['subtitle'], $link, $thumb);
			}
			
		}

		public function delTree($dir) {
		   $files = array_diff(scandir($dir), array('.','..'));
		    foreach ($files as $file) {
		    	if(is_dir("$dir/$file")){
		      		$files2 = array_diff(scandir($dir.'/'.$file), array('.','..'));
		      		foreach ($files2 as $value) {
		      			unlink($dir.'/'.$file.'/'.$value);
		      		}
		      		rmdir($dir.'/'.$file);
		      	}else{
		      		unlink($dir.'/'.$file);
		      	}
		    }
		    return rmdir($dir);		
		} 


		/**
		*	check if pics have been removed or added to a gallery
		*
		*/
		public function checkPic($content, $folder){
			$sql = $this->_connexion->prepare("SELECT pictures.id, link FROM pictures JOIN galleries ON pictures.gallery = galleries.id WHERE galleries.folder = :folder");
			$sql-> bindParam('folder', $folder, PDO::PARAM_STR);
			$sql-> execute();
			$rows = $sql->fetchAll(PDO::FETCH_ASSOC);
			$picslistAdd = array();
			$picslistRemove = array();
			foreach ($rows as $value) {
				$picslistAdd[] = $value['link'];
				$picslistRemove[$value['id']] = $value['link'];
			}

			$folderpics = array();
			$gallery_content = $this->getAllPics($content);
			foreach ($gallery_content as $value) {
				$folderpics[$value] = 'photos/'.$folder.'/'.$value;
			}

			if(empty($folderpics)){
				// $this->deleteGallery($folder);
				$this->delTree("photos/".$folder);
			}


			// check if pics have been added
			$list = array();
			$cpt = 0;
			foreach ($folderpics as $key => $value) {
				if (!in_array($value, $picslistAdd)) {
					$id = $this->getGalleryId($folder);
					$list[$cpt]['name'] = $key;
					$list[$cpt]['link'] = $value;
					$list[$cpt]['folder'] = $folder;
					$list[$cpt]['gallery'] = $id;
					$cpt++;
				}
			}
			if($list)
				$this->addPicture($list);

			//check if pics have been removed
			$list2 = array();
			foreach ($picslistRemove as $key => $value) {
				if(!in_array($value, $folderpics)){
					$list2[$value] = $key;
				}
			}
			if($list2){
				foreach ($list2 as $key => $value) {
					$this->removePic($value, $key);
				}
			}

		}

		public function addPicture($list){
			foreach ($list as $key => $value) {
				$dir = 'photos/'.$value['folder'];
				$thumb_dir = $dir.'/thumbs';
				$thumb = $thumb_dir.'/'.$value['name'];
				$subtitle = "";
				$this->createThumbnail($value['name'], $dir.'/', $thumb_dir );
				$this->insertPics($value['gallery'], $value['name'], $subtitle, $value['link'], $thumb);
			}
		}

		/**
		* remove pics info from BDD 
		* and remove thumbnail of the pics
		*/

		public function removePic($id, $link){
			$sql_del_com = $this->_connexion->prepare("DELETE FROM comments WHERE pics = :id");
			$sql_del_com-> bindParam('id', $id, PDO::PARAM_INT);
			$sql_del_com-> execute();

			$sql_del_pic = $this->_connexion->prepare("DELETE FROM pictures WHERE id = :id");
			$sql_del_pic-> bindParam('id', $id, PDO::PARAM_INT);
			$sql_del_pic-> execute();
			$tmp = preg_split("/\//", $link);
			$thumb_link = $tmp[0].'/'.$tmp[1].'/thumbs'.'/'.$tmp[2];
			if(file_exists($thumb_link))
				unlink($thumb_link);

		}


		/**
			 metadata part
		*/

		/**
			set metadata in bdd Txt way
		*/

		public function metadata($gallery_content){
			return in_array('metadata.txt', $gallery_content);	
		}

		public function newGalleryTxt($file){
			$info = file($file.'/metadata.txt');
			foreach ($info as $value) {
					$lines = preg_split("/\n/", $value, null);
					$lines_utf8[] = utf8_encode($lines[0]);
			}
			$lines_utf8 = array_filter($lines_utf8);

			$title_line = preg_split("/\|/", $lines_utf8[0], -1, PREG_SPLIT_NO_EMPTY);
			if (($title_line[0] == "title") && (isset($title_line[1]))) {
				$title_line = explode("@", $title_line[1]);
				$folder_name = preg_split("/\//", $file);
				$folder_name = $folder_name[1];	
				$gallery_title = (isset($title_line[0]))?(htmlspecialchars($title_line[0], ENT_QUOTES)):(htmlspecialchars($folder_name, ENT_QUOTES));
				$gallery_subtitle = (isset($title_line[1]))?(htmlspecialchars($title_line[1], ENT_QUOTES)):"";
			}else{
				$gallery_title = preg_split("/\//", $file);
				$gallery_title = htmlspecialchars($gallery_title[1], ENT_QUOTES);
				$gallery_subtitle = "";
				$folder_name = $gallery_title;
			}

			$this->insertGallery($folder_name, $gallery_title, $gallery_subtitle);
			$id = $this->getGalleryId($folder_name);
			$this->addPicturesTxt($file, $lines_utf8, $id);
		}

		public function getPictureMetatdata($lines_utf8){
			$metadata = array();
			foreach ($lines_utf8 as $key => $value) {
				$split = preg_split("/\|/", $value, -1, PREG_SPLIT_NO_EMPTY);
				if (((isset($split)) || ($split[0] != "title")) && (isset($split[1]))) {
					$metadata[$split[0]] = $split[1];
				}		
			}
			return $metadata;
		}

		public function addPicturesTxt($dir, $lines_utf8, $id){
			
			$gallery_pictures = $this->getAllPics($dir);

			// if pictures name from folder exist in metadata
			// name and metadata are add to the array
			// if not exist name is add
			$metadata = $this->getPictureMetatdata($lines_utf8);
			$pics_to_add = array();
			foreach ($gallery_pictures as $value) {
				$pics_to_add[$value] = (array_key_exists($value, $metadata))?$metadata[$value]:$value.'::';
			}
			
			// insert in BDD pics name and data
			foreach ($pics_to_add as $key => $value) {
				$link = $dir.'/'.$key;
				$thumb_dir = $dir.'/thumbs';
				$thumb = $thumb_dir.'/'.$key;
				$escape_value = htmlspecialchars($value, ENT_QUOTES);
				$this->createThumbnail($key, $dir.'/', $thumb_dir );
				$split = preg_split("/::/", $escape_value, -1, PREG_SPLIT_NO_EMPTY);
				$pic_title = (isset($split[0]))?$split[0]:$key;
				$pic_subtitle = (isset($split[1]))?$split[1]:"";
				$this->insertPics($id, $pic_title, $pic_subtitle, $link, $thumb);
			}

			unlink($dir.'/metadata.txt');


		}

		/**
			set metadata in bdd Json way
		*/

		public function metadataJson($gallery_content){
			return in_array('metadata.json', $gallery_content);	
		}

		public function newGalleryJson($file){
			$path = $file.'/metadata.json';
			$info = json_decode(utf8_encode(file_get_contents($path)));
			$title = htmlspecialchars($info->{'title'}, ENT_QUOTES);
			$subtitle = htmlspecialchars($info->{'description'}, ENT_QUOTES);
			$folder_name = preg_split("/\//", $file);
			$folder_name = $folder_name[1];
			if($title == ""){
				$title = $folder_name;
			}

			$this->insertGallery($folder_name, $title, $subtitle);
			$id = $this->getGalleryId($folder_name);
			$this->addPicturesJson($file, $info->{'images'}, $id);
		}

		public function addPicturesJson($dir, $images, $id){
			$gallery_pictures = $this->getAllPics($dir);

			foreach ($images as $key => $value) {
				$array[$value->{'filename'}]['title'] = $value->{'title'};
				$array[$value->{'filename'}]['subtitle'] = $value->{'description'};
			}

			$pics_to_add = array();
			foreach ($gallery_pictures as $value) {
				if (array_key_exists($value, $array)) {
					$pics_to_add[$value]['title'] = htmlspecialchars($array[$value]['title'], ENT_QUOTES);
					$pics_to_add[$value]['subtitle'] = htmlspecialchars($array[$value]['subtitle'], ENT_QUOTES);
					if ($pics_to_add[$value]['title'] == "") {
						$pics_to_add[$value]['title'] = htmlspecialchars($value, ENT_QUOTES);
					}
				}else{
					$pics_to_add[$value]['title'] = htmlspecialchars($value, ENT_QUOTES);
					$pics_to_add[$value]['subtitle'] = "";
				}
			}

			foreach ($pics_to_add as $key => $value) {
				$link = $dir.'/'.$key;
				$thumb_dir = $dir.'/thumbs';
				$thumb = $thumb_dir.'/'.$key;
				$this->createThumbnail($key, $dir.'/', $thumb_dir );
				$this->insertPics($id, $value['title'], $value['subtitle'], $link, $thumb);
			}

			unlink($dir.'/metadata.json');
			
		}

	}



?>