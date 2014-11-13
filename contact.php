<?php 
ob_start(); 
require_once('php/param.class.php');
$param = new Param();
?><!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="style/favicon.ico" rel="icon">
    <meta name="author" lang="fr" content="Padow"> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php $param->setPageTitle(); ?> - contact</title>
    <!-- Bootstrap -->
    <link href="style/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="style/index.css">

  </head>
  <body ondragstart="return false;" ondrop="return false;">
    <div class="body">
    <?php 

    ?>
 <div class="wrap">
  <div class="content">
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><span class="glyphicon glyphicon-th"></span> Galeries</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="bio.php"><span class="glyphicon glyphicon-leaf"></span> A propos</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
      <div class="container">
      <?php         
        if (isset($_POST['envoyer'])) {
          extract($_POST);
          $Warning = false;
          $error = "";
          if (filter_var($mailfrom, FILTER_VALIDATE_EMAIL)) {
              $valide = true;
          }else{
            $valide = false;
            $Warning = true;
            $error .= '<ul>Adresse mail non valide.</ul>';          
          }

          if (!preg_match("/\S/", $name)) {
            $valide = false;
            $Warning = true;
            $error .= '<ul>Veuillez entrer votre nom.</ul>';   
          }

          if (!preg_match("/\S/", $subject)) {
            $valide = false;
            $Warning = true;
            $error .= '<ul>Le sujet ne peut être vide.</ul>';   
          }

          if (!preg_match("/\S/", $message)) {
            $valide = false;
            $Warning = true;
            $error .= '<ul>Le message ne peut être vide.</ul>';   
          }
         
          if ($valide) {
            $to = $param->getEmail();
            $headers = "From: $name\r\nReply-to: $mailfrom";
            $sujet = $subject." - via site photo : ".$mailfrom;
            if(@mail($to, $sujet, $message, $headers, '-f'.$mailfrom)){
              $info = true;
              $confirm = "Votre message à bien été envoyé.";
              unset($_POST);
            }else{
              $info = false;
              $confirm = "Une erreur est survenu, votre mail n'a pu être envoyé.";
            }
          }         
      }
        require_once('php/contact.class.php');
        $contact = new Contact(isset($_POST)?$_POST:"");
    ?>
    <div class="col-md-12">
      <?php if (isset($Warning) && $Warning) { ?>
      <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span><span class="sr-only">Close</span></button>
        <p><strong>Attention !</strong><p>
        <p><?php echo $error; ?></p>
      </div>
      <?php } ?>

     
    </div>
    <div class="col-md-6">
      <div class="col-md-12">
          <?php if (isset($confirm)) { echo $contact->confirmation($info, $confirm);} ?>
      </div>
          <form role="form" method="post">
          <?php
            echo $contact->text('name', 'Nom');
            echo $contact->email('mailfrom', 'Email');
            echo $contact->text('subject', 'Sujet');
            echo $contact->textarea('message', 'Message');
          ?>
          <button type="submit" name="envoyer" class="btn btn-default"><span class="glyphicon glyphicon-send"></span> Envoyer</button>
        </form>
        </div>  
      </div>
     
      

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="js/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="style/bootstrap/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="js/jquery-ui/jquery-ui.js"></script>
      <script src="js/index.js"></script>
  </div>
  <div class="footer">
    <div class="container">
      <div class="col-md-12 padd">
        <div class="col-md-8">
         <?php $param->setFooter(); ?>
        </div>
        <div class="col-md-4 pull-right">  
          © 2014 <a href="http://steamcommunity.com/id/padow/" target="_blank">Padow</a>. All rights reserved.
        </div>
      </div>
    </div>
  </div>
</div>
</div>
  </body>
</html>
<?php ob_end_flush(); ?>
<span class="del"></span>