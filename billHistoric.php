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

    $query = $bdd->prepare("SELECT * FROM facture WHERE entreprise = ?");
    $query->execute([$_SESSION['siret']]);

    $totalPrice = 0;
    $totalParcel = 0;

    while ($bill=$query->fetch()) {
      $totalPrice+= $bill['montant'];
      $totalParcel+= $bill['nbColis'];
    }

     ?>
    <div class="container mt-5">
      <h1 class="banner-item text-center">Vos factures</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center">Montant total des factures payées : <?php echo $totalPrice; ?> €</h2>
        <h2 class="banner-item w-50 text-center">Nombre total de colis traités : <?php echo $totalParcel; ?> </h2>
      </div>
      <?php if($totalParcel != 0){ ?>
      <h1 class="banner-item text-center mt-5">Historique des factures</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col">Montant</th>
            <th scope="col">Nombre de colis</th>
            <th scope="col">Date</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $query = $bdd->prepare("SELECT * FROM facture WHERE entreprise = ? ORDER BY date DESC");
          $query->execute([$_SESSION['siret']]);

          while ($bill=$query->fetch()) {
            echo'
            <tr>
              <td>'.$bill['montant'].' €</td>
              <td>'.$bill['nbColis'].' colis</td>
              <td>'.date('d/m/Y', strtotime($bill['date'])).'</td>
              <td><button type="button" class="btn btn-primary" onclick="toPDF('.$bill['id'].')"><a class="serviceslink">Télécharger en pdf</a></button></td>
            </tr>
            ';
          }
           ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
    <script type="text/javascript">
    function toPDF(idBill){
      $.ajax({
         url : 'toPDF.php',
         type : 'POST',
         data : 'idBill='+idBill,
         dataType : 'html',
         success : function(result){
           console.log(result);
           window.open('billPDF.php', '_blank');
         }
      });
    }
    </script>
  </body>

</html>
