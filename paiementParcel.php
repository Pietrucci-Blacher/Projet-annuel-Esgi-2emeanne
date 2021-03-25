<?php
  session_start();
  $_SESSION['siret']= '754879';
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
      <h1 class="banner-item text-center">Paiement de vos colis</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center">Total à payer : ... €</h2>
        <h2 class="banner-item w-50 text-center">Nombre de colis en attente : ... </h2>
      </div>
      <div class="d-grid gap-2 mx-auto">
        <button id="pay" type="button" class="btn btn-primary mt-5 btn-lg"><a class="serviceslink h4">Payer les colis</a></button>
      </div>
      <h1 class="banner-item text-center mt-5">Détail du paiement</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col">Nombre de colis</th>
            <th scope="col">Mode de livraison</th>
            <th scope="col">Prix par rapport au poids</th>
            <th scope="col">Total</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </body>

</html>
