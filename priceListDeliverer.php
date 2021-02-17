<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
    <?php require_once('include/header.php'); ?>
  <body>
    <div class="container">
      <h1 class="text-center mt-5 banner-item">Grille tarifaire de rémunération des livreurs</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <tbody>
          <tr>
            <td>Nombre de km parcourus</td>
            <td colspan="2">0.36 € / km</td>
          </tr>
          <tr>
            <td>Nombre de colis livrés</td>
            <td colspan="2">1.90 € / colis</td>
          </tr>
          <tr>
            <td>Prime charge lourde (colis > 30 kg)</td>
            <td colspan="2">3 € par tranche de 22kg / colis</td>
          </tr>
          <tr>
            <td rowspan="5">Prime mensuelle <br>(nombre de colis livrés sur l’ensemble des colis affectés)</td>
            <td>> 87 %</td>
            <td>+ 10 % sur le total du mois</td>
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
            <td>- 15 % sur le total du mois</td>
          </tr>
        </tbody>
    </div>
  </body>
</html>
