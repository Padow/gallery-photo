<?php 
	$timeleft = $_POST['timeleft'];
?>
<div class="alert alert-warning alert-dismissible"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span><span class="sr-only">Close</span></button> <strong>Attention</strong> Vous devez attendre 20s entre chaque commentaires (<?php echo $timeleft.'s'; ?>)</div>