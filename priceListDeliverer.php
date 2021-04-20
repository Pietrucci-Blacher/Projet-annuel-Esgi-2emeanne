<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
      <script src="js/translate.js"></script>
  </head>
    <?php require_once('include/header.php'); ?>
  <body>
    <div class="container">
      <h1 class="text-center mt-5 banner-item" langtrad="GRILLE">Grille tarifaire de rémunération des livreurs</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <tbody>
          <tr>
            <td langtrad="1">Nombre de km parcourus</td>
            <td colspan="2">0.36 € / km</td>
          </tr>
          <tr>
            <td langtrad="2">Nombre de colis livrés</td>
            <td colspan="2" langtrad="3">1.90 € / colis</td>
          </tr>
          <tr>
            <td langtrad="4">Prime charge lourde (colis > 30 kg)</td>
            <td colspan="2" langtrad="5">3 € par tranche de 22kg / colis</td>
          </tr>
          <tr>
            <td rowspan="5" langtrad="6">Prime mensuelle <br langtrad="9">(nombre de colis livrés sur l’ensemble des colis affectés)</td>
            <td>> 87 %</td>
            <td langtrad="7">+ 10 % sur le total du mois</td>
          </tr>
          <tr>
            <td>72 - 87 %</td>
            <td>120 €</td>
          </tr>
          <tr>
            <td>60 - 72 %</td>
            <td>50 €</td>
          </tr>
          <tr>
            <td>< 60 %</td>
            <td>Ø</td>
          </tr>
          <tr>
            <td>< 10 %</td>
            <td langtrad="8">- 15 % sur le total du mois</td>
          </tr>
        </tbody>
    </div>
  </body>
</html>
