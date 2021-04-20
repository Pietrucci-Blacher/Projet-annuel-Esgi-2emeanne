<?php
  session_start();
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

    $query = $bdd->prepare("SELECT COUNT(id) FROM COLIS WHERE depot is NULL");
    $query->execute();
    $countParcel = $query->fetch();

     ?>
     <div class="container mt-5">
       <h1 class="banner-item text-center mb-5">Attribuer les colis aux dépots</h1>
       <?php if($countParcel[0] != 0){ ?>
         <h2 class="banner-item text-center mt-5">Colis en attente d'attribution : <?php echo $countParcel[0] ?></h2>
       <div class="d-grid gap-2 mx-auto mt-5 col-6">
         <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="processParcel()"><a class="serviceslink h4">Attribuer</a></button>
       </div>
       <?php }else{ ?>
          <h2 class="banner-item text-center mt-5">Aucun colis en attente</h2>
        <?php } ?>
    </div>
    <script type="text/javascript">
      function processParcel(idParcel,idClient){
        $.ajax({
           url : 'parcelToDepositProcess.php',
           success : function(result){
             document.location.reload();
           }
        });
      }
    </script>
  </body>
</html>
