<?php
session_name('IDSESSION');
session_start();
$_SESSION['display'] = "displayComs();";
require_once('../../php/connexion.class.php');
require_once('admin.class.php');

$comments = new Admin();
$comments->update();

?>

   <h3> Galleries updated.</h3>


<script>setTimeout(function() { location.reload(); }, 2000);</script>
