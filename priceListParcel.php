<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
    <?php require_once('include/header.php'); ?>
  <body>
    <div class="container">
      <h1 class="text-center mt-5 banner-item">Grille tarifaire des colis</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col">Poids maximum</th>
            <th scope="col">Express (2j ouvrés)</th>
            <th scope="col">Standard (5j ouvrés)</th>
          </tr>
        </thead>
        <tbody>

          <?php
          require_once('include/connexionbdd.php');

          $bdd = connexionBDD();

          $req = $bdd->prepare("SELECT poidsMax,max_date,prixExpress,prixStandard FROM tarifcolis t INNER JOIN (SELECT poidsMax as pdM,MAX(date) as max_date FROM tarifcolis GROUP BY pdM)a ON a.pdM = t.poidsMax and a.max_date = date ORDER BY a.pdM");
          $req->execute();

          $count = 0;
          while($price = $req->fetch()){
            $count +=1;
            if($price['poidsMax'] == 31){
              echo'
              <tr>
                <td>Au dessus de 30 kg</td>
                <td>'.$price['prixExpress'].'</td>
                <td>'.$price['prixStandard'].'€ par tranche de 20 kg</td>
              </tr>
              ';
            }else{
              echo'
              <tr>
                <td>'.$price['poidsMax'].' kg</td>
                <td>'.$price['prixExpress'].' €</td>
                <td>'.$price['prixStandard'].' €</td>
              </tr>
              ';
            }
          }
           ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
