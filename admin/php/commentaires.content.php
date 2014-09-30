<?php  
session_name('IDSESSION');
session_start();
$_SESSION['display'] = "displayComs();";
require_once('../../php/connexion.class.php'); 
require_once('admin.class.php');

$comments = new Admin();
$comments->getAllComments();

?>

<div class="form-backa">
  <div class="del-conf">
        Supprimer le commentaire?
        <div class="sep"></div>
        <button class="btn btn-default btn-sm" id="delButton" onclick="deleteComment()"><span class="glyphicon glyphicon-ok"></span> Oui</button>
        <button class="btn btn-default btn-sm" onclick="hideconfirmMess()"><span class="glyphicon glyphicon-remove"></span> Non</button>               
  </div>
</div>