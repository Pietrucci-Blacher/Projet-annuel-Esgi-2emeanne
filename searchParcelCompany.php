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

      <div class="d-flex mt-4">
        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Rechercher par :</p>
          <select class="form-select" aria-label="Default select example">
            <option selected>N'importe</option>
            <option value="1">Nom</option>
            <option value="2">CodePostal</option>
            <option value="3">Référence</option>
          </select>
        </div>

        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Mode de livraison :</p>
          <select class="form-select" aria-label="Default select example">
            <option selected>N'importe</option>
            <option value="1">Standard</option>
            <option value="2">Express</option>
          </select>
        </div>

        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Status du paiement :</p>
          <select class="form-select" aria-label="Default select example">
            <option selected>N'importe</option>
            <option value="1">Payé</option>
            <option value="2">Paiement en attente</option>
          </select>
        </div>

      </div>

      <div class="d-grid gap-2 mx-auto">
        <button id="submit" type="button" class="btn btn-primary mt-5 btn-lg"><a class="serviceslink">VALIDER</a></button>
      </div>
    </div>
  </body>
</html>
