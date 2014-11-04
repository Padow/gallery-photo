<?php 
ob_start(); 
require_once('php/param.class.php');
$param = new Param();
?>
<!DOCTYPE html>
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
      require_once('php/connexion.class.php');
      require_once('php/gallery.class.php');
      
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

          if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mailfrom)){
            $valide = true;
          }else{
            $valide = false;
            $errormail = 'adresse mail non valide';
          }

          if($valide){
            $headers = "From: $name <$mailfrom>";
            $sujet = $subject." - via site photo : ".$mailfrom;
            if(mail($to, $sujet, $message, $headers)){
              $confirm = "Votre message à bien été envoyé.";
              unset($name);
              unset($subject);
              unset($sujet);
              unset($mailfrom);
              unset($message);
            }else{
              $confirm = "Une erreur est survenu, votre mail n'a pu être envoyé.";
            }
          }

         
           
        }
      ?>
        <div class="col-md-6">
    <div class="col-md-12">
          <?php if(isset($confirm)){echo "<p><strong>".$confirm."</strong></p>";} ?>
        </div>
          <form role="form" method="post">
          <input type="text" id="to" name="to" value="<?php echo $param->getEmail(); ?>" class="form-control hidden">
          <label for="name">Nom</label>
          <input type="text" id="name" name="name" value="<?php if(isset($name)){echo $name;} ?>" class="form-control" required>
          <label for="mail">e-mail</label>
          <input type="email" id="mail" name="mailfrom" value="<?php if(isset($mailfrom)){echo $mailfrom;} ?>" class="form-control" required>
          <div class="col-md-12">
            <span class="error"><?php if(isset($errormail)){echo $errormail;} ?></span>
          </div>
          
          <label for="subject">Sujet</label>
          <input type="text" id="subject" name="subject" value="<?php if(isset($subject)){echo $subject;} ?>" class="form-control" required>
          <label form="message">Message</label>
          <textarea id="message" name="message" class="contactarea" required ><?php if(isset($message)){echo $message;} ?></textarea>
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