<?php
  $_SESSION['siret']= 754879;
    if(empty($_SESSION['siret'])){
        header('Location: connect.php');
    }
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <div class="container mt-5">
      <h1 class="banner-item text-center mb-3">Veuillez entrer les informations relatives au colis</h1>
      <div class="input-group input-group-lg">
        <input id="name" type="text" class="form-control text-center mt-5" placeholder="Recherche">
      </div>
      <div class="d-grid gap-2 mx-auto">
        <button id="submit" type="button" class="btn btn-primary mt-5 btn-lg"><a class="serviceslink">VALIDER</a></button>
      </div>
    </div>
  </body>
</html>
