<?php session_start();
if(isset($_SESSION['rank'])){
  if($_SESSION['rank'] != "admin"){
      header('Location: index.php');
      exit();
  }
}else{
  header('Location: index.php');
  exit();
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Historique des prix </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="moreInfo">
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <h1 class="text-center mt-5 banner-item">Gérer les tarifs des colis</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col">Poids maximum</th>
            <th scope="col">Express (2j ouvrés)</th>
            <th scope="col">Standard (5j ouvrés)</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>

          <?php
          require_once('include/connexionbdd.php');

          $bdd = connexionBDD();

          $req = $bdd->prepare("SELECT poidsMax,id,max_date,prixExpress,prixStandard FROM tarifcolis t INNER JOIN (SELECT poidsMax as pdM,MAX(date) as max_date FROM tarifcolis GROUP BY pdM)a ON a.pdM = t.poidsMax and a.max_date = date ORDER BY a.pdM");
          $req->execute();


          while($price = $req->fetch()){
            if($price['poidsMax'] == 31){
              echo'
              <tr>
                <td>Au dessus de 30 kg</td>
                <td>'.$price['prixExpress'].'</td>
                <td>'.$price['prixStandard'].'€ par tranche de 20 kg</td>
                <td><button type="button" name="button" class="btn btn-primary me-5" onclick="showHistory('.$price['poidsMax'].')">Voir l\'historique</button>
                <button type="button" name="button" class="btn btn-primary" onclick="priceDisplayModal('.$price['id'].','.$price['poidsMax'].')">Modifier</button></td>
              </tr>
              ';
            }else{
              echo'
              <tr>
                <td>'.$price['poidsMax'].' kg</td>
                <td>'.$price['prixExpress'].' €</td>
                <td>'.$price['prixStandard'].' €</td>
                <td><button type="button" name="button" class="btn btn-primary me-5" onclick="showHistory('.$price['poidsMax'].')">Voir l\'historique</button>
                <button type="button" name="button" class="btn btn-primary" onclick="priceDisplayModal('.$price['id'].','.$price['poidsMax'].')">Modifier</button></td>
              </tr>
              ';
            }
          }
           ?>
        </tbody>
      </table>
    </div>
    <script type="text/javascript">
      function showHistory(weight){
        $.ajax({
           url : 'priceHistory.php',
           type : 'POST',
           data : 'weight='+weight,
           dataType : 'html',
           success : function(result){
             document.getElementById('moreInfo').innerHTML = result;
             if(weight == 31){
               document.getElementById('exampleModalLabel').innerHTML = "Historique des prix pour 'supérieur à 30 kg' :";
             }else{
               document.getElementById('exampleModalLabel').innerHTML = "Historique des prix pour "+weight+" kg :";
             }
             $(function () {
                $('#exampleModal').modal('toggle');
             });
           }
        });
      }

      function priceDisplayModal(idPrice,weight){
        $.ajax({
           url : 'priceDisplayModal.php',
           type : 'POST',
           data : 'idPrice='+idPrice,
           dataType : 'html',
           success : function(result){
             document.getElementById('moreInfo').innerHTML = result;
             if(weight == 31){
               document.getElementById('exampleModalLabel').innerHTML = "Modifier le prix pour 'supérieur à 30 kg' :";
             }else{
               document.getElementById('exampleModalLabel').innerHTML = "Modifier le prix pour "+weight+" kg :";
             }
             $(function () {
                $('#exampleModal').modal('toggle');
             });
           }
        });
      }

      function modifyParcel(weight){
        let priceE;

        if(weight == 31 ){
          priceE = 'Impossible';
        }else{
          priceE = document.getElementById('inputE').value;
        }

        let priceS = document.getElementById('inputS').value;

        if(priceE == "" || priceS == ""){
          alert("Erreur dans les champs de saisie");
        }else{
          $.ajax({
             url : 'modifyPrice.php',
             type : 'POST',
             data : 'priceE='+priceE+'&priceS='+priceS+'&weight='+weight,
             dataType : 'html',
             success : function(result){
               document.location.reload();
             }
          });
        }
      }
    </script>
  </body>
</html>
