<?php 
	require_once('../../php/connexion.class.php'); 
	require_once('admin.class.php');

		$id = $_GET['key'];
		$path = $_GET['path'];

		$comments = new Admin();
		$comments->changeGalleryThumb($path, $id);
?>