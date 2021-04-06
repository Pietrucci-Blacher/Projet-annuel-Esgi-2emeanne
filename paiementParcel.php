<?php
session_start();
if(empty($_SESSION['siret'])){
    header('Location: enterpriseform.php');
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

      $totalPrice = 0;
      $totalParcel = 0;
      $queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non'");
      $queryParcel->execute([$_SESSION['siret']]);

      while($parcel = $queryParcel->fetch()){
        $totalPrice += $parcel['prix'];
        $totalParcel +=1;
      }
     ?>
    <div class="container mt-5">
      <h1 class="banner-item text-center">Paiement de vos colis</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center">Total à payer : <?php echo $totalPrice ?> €</h2>
        <h2 class="banner-item w-50 text-center">Nombre de colis en attente : <?php echo $totalParcel ?> </h2>
      </div>
      <?php if($totalParcel != 0){ ?>
      <div class="d-grid gap-2 mx-auto">
        <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="payBill()"><a class="serviceslink h4">Payer les colis</a></button>
      </div>
      <h1 class="banner-item text-center mt-5">Détails du paiement</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col">Référence</th>
            <th scope="col">Mode de livraison</th>
            <th scope="col">Poids</th>
            <th scope="col">Prix</th>
          </tr>
        </thead>
        <tbody>
            <?php
            $queryParcelBis = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non' ORDER BY poids");
            $queryParcelBis->execute([$_SESSION['siret']]);

            while($parcelBis = $queryParcelBis->fetch()){
              echo'
                  <tr>
                    <td>'.$parcelBis['refQrcode'].'</td>
                    <td>'.$parcelBis['modeLivraison'].'</td>
                    <td>'.$parcelBis['poids'].'</td>
                    <td>'.$parcelBis['prix'].' €</td>
                  </tr>
                  ';
            }

            echo '<tr>
                <td colspan="2"></td>
                <th scope="col">Total à payer</th>
                <td class="fw-bold">'.$totalPrice.' €</td>
                </tr>';
             ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
    <script type="text/javascript">
      function payBill(){
        $.ajax({
           url : 'generateBill.php',
           success : function(result){
             window.location.href = "billHistoric.php";
           }
        });
      }
    </script>
  </body>

</html>
