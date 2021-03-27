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
    <?php
      require_once('include/connexionbdd.php');

      $bdd = connexionBDD();

      $priceParcelExpress =[];
      $priceParcelStandard=[];
      $queryPrice= $bdd->prepare("SELECT * FROM tarifcolis");
      $queryPrice->execute();

      while ($price = $queryPrice->fetch()) {
        array_push($priceParcelExpress,$price['prixExpress']);
        array_push($priceParcelStandard,$price['prixStandard']);
      }

      $queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND status = 'En attente du partenaire'");
      $queryParcel->execute([$_SESSION['siret']]);

      $nbParcelExpress = array(0,0,0,0,0,0,0,0,0,0);
      $nbParcelStandard=array(0,0,0,0,0,0,0,0,0,0);
      $weightParcel = array(0.5,1,2,3,5,7,10,15,30);
      $totalStdSup30 = 0;
      $totalPrice = 0;

      function calculateTotalPrice($nbParcel,$priceParcel){
        global $totalStdSup30;
        global $totalPrice;

        for ($i=0; $i < sizeof($nbParcel) ; $i++) {
          if($nbParcel[$i]!=0){
             if($i == 9){
               $totalPrice += $totalStdSup30;
            }else{
                $totalPrice+= $nbParcel[$i]*$priceParcel[$i];
            }
          }
        }
      }

      function cmpParcelWeight($weight,$nbParcel,$mode){
        global $nbParcelExpress;
        global $nbParcelStandard;
        global $weightParcel;
        global $priceParcelStandard;
        global $totalStdSup30;

        for ($i=0; $i < sizeof($weightParcel) ; $i++) {
          if ($weight<=$weightParcel[$i]) {
            $nbParcel[$i] += 1;
            break;
          }else if($weight>$weightParcel[8]){
            $nbParcel[9] += 1;
            $totalStdSup30+=$weight%20*$priceParcelStandard[9];
          }
        }
        if($mode == 1){
          $nbParcelExpress = $nbParcel;
        }elseif($mode == 2){
          $nbParcelStandard = $nbParcel;
        }
      }

      $countParcel = 0;

      while($parcel = $queryParcel->fetch()){
        if($parcel['modeLivraison']=='express'){
          cmpParcelWeight($parcel['poids'],$nbParcelExpress,1);
        }elseif($parcel['modeLivraison']=='standard'){
          cmpParcelWeight($parcel['poids'],$nbParcelStandard,2);
        }
        $countParcel += 1;
      }

      calculateTotalPrice($nbParcelStandard,$priceParcelStandard);
      calculateTotalPrice($nbParcelExpress,$priceParcelExpress);
     ?>
    <div class="container mt-5">
      <h1 class="banner-item text-center">Paiement de vos colis</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center">Total à payer : <?php echo $totalPrice ?> €</h2>
        <h2 class="banner-item w-50 text-center">Nombre de colis en attente : <?php echo $countParcel ?> </h2>
      </div>
      <div class="d-grid gap-2 mx-auto">
        <button id="pay" type="button" class="btn btn-primary mt-5 btn-lg"><a class="serviceslink h4">Payer les colis</a></button>
      </div>
      <h1 class="banner-item text-center mt-5">Détails du paiement</h1>
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
            <?php

            function displayInfo($nbParcel,$priceParcel,$mode){
              global $weightParcel;
              global $totalStdSup30;

              if($mode == 1){
                $modeDelivery = "express";
              }elseif($mode == 2){
                $modeDelivery = "standard";
              }
              for ($i=0; $i < sizeof($nbParcel) ; $i++) {
                if($nbParcel[$i]!=0){
                  if($i == 0){
                    echo'
                    <tr>
                      <td>'.$nbParcel[$i].'</td>
                      <td>'.$modeDelivery.'</td>
                      <td>'.$priceParcel[$i].' € (colis inférieur à '.$weightParcel[$i].' kg)</td>
                      <td>'.$nbParcel[$i]*$priceParcel[$i].' €</td>
                    </tr>
                    ';
                  }else if($i == 9){
                    echo'
                    <tr>
                      <td>'.$nbParcel[$i].'</td>
                      <td>'.$modeDelivery.'</td>
                      <td>'.$priceParcel[$i].' € par tranche de 20 kg (colis supérieur à '.$weightParcel[$i-1].' kg)</td>
                      <td>'.$totalStdSup30.' €</td>
                    </tr>
                    ';
                  }else{
                    echo'
                    <tr>
                      <td>'.$nbParcel[$i].'</td>
                      <td>'.$modeDelivery.'</td>
                      <td>'.$priceParcel[$i].' € (colis compris entre '.$weightParcel[$i-1].' kg et '.$weightParcel[$i].' kg)</td>
                      <td>'.$nbParcel[$i]*$priceParcel[$i].' €</td>
                    </tr>
                    ';
                  }
                }
              }
            }

            displayInfo($nbParcelExpress,$priceParcelExpress,1);
            displayInfo($nbParcelStandard,$priceParcelStandard,2);

            echo '<tr>
                <td colspan="2"></td>
                <th scope="col">Total à payer</th>
                <td class="fw-bold">'.$totalPrice.' €</td>
                </tr>';
             ?>
        </tbody>
      </table>
    </div>
  </body>

</html>
