<?php
require_once('include/utilities/banuser.php');
checkbanuser();
?>
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
    <?php
    require_once('include/connexionbdd.php');

    $bdd = connexionBDD();

    $deliver=$bdd->prepare("SELECT id FROM livreur WHERE client = ?");
    $deliver->execute([$_SESSION['id']]);
    $idDeliver=$deliver->fetch();

    $_SESSION['idDeliver'] = $idDeliver['id'];

    $query = $bdd->prepare("SELECT * FROM salaire WHERE livreur = ?");
    $query->execute([$_SESSION['idDeliver']]);

    $totalPrice = 0;
    $totalParcel = 0;

    while ($bill=$query->fetch()) {
      $totalPrice+= $bill['montant'];
      $totalParcel+= $bill['nbColis'];
    }

     ?>
    <div class="container mt-5">
      <h1 class="banner-item text-center" langtrad="FACT">Vos salaires</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center" langtrad="MONTFACT">Montant total : <?php echo $totalPrice; ?> €</h2>
        <h2 class="banner-item w-50 text-center" langtrad="NUMCOL">Nombre de colis livrés : <?php echo $totalParcel; ?> </h2>
      </div>
      <?php if($totalParcel != 0){ ?>
      <h1 class="banner-item text-center mt-5" langtrad="HISTOFACT">Historique des salaires</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col" langtrad="MONT">Montant</th>
            <th scope="col" langtrad="DA">Date</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php

          $query = $bdd->prepare("SELECT * FROM salaire WHERE livreur = ? ORDER BY date DESC");
          $query->execute([$_SESSION['idDeliver']]);

          while ($salary=$query->fetch()) {
            echo'
            <tr>
              <td>'.$salary['montant'].' €</td>
              <td>'.date('d/m/Y', strtotime($salary['date'])).'</td>
              <td><button type="button" class="btn btn-primary col-8" onclick="toPDF('.$salary['id'].')"><a class="serviceslink">Télécharger en pdf</a></button></td>
            </tr>
            ';
          }
           ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
    <script type="text/javascript">
    function toPDF(idSalary){
      $.ajax({
         url : 'salaryToPdf.php',
         type : 'POST',
         data : 'idSalary='+idSalary,
         dataType : 'html',
         success : function(result){
           window.open('salaryPdf.php', '_blank');
         }
      });
    }
    </script>
  </body>

</html>
