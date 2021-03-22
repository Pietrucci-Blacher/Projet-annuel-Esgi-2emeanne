<?php
  $_SESSION['siret']= 754879;
    if(empty($_SESSION['siret'])){
        header('Location: connect.php');
    }
?>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <meta charset="utf-8"/>
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <script>

      function showMore(idParcel){

        $.ajax({
           url : 'parcelInfo.php',
           type : 'POST',
           data : 'idParcel='+idParcel,
           dataType : 'html',
           success : function(result){
             document.getElementById('moreInfo').innerHTML = result;
             $(function () {
                $('#exampleModal').modal('toggle');
             });
           }
        });
      }

      function delParcel(idParcel,idClient){
        $.ajax({
           url : 'deleteParcel.php',
           type : 'POST',
           data : 'idParcel='+idParcel+'&idClient='+idClient,
           dataType : 'html',
           success : function(result){
             if(result == 'success'){
               document.getElementById(idParcel).remove();
             }
           }
        });
      }

    </script>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Détails du colis</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="moreInfo">
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-5">
      <h1 class="banner-item text-center mb-5">Liste des colis en cours</h1>

      <ul class="nav nav-tabs result" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link tabLink active" id="requirePaiement-tab" data-bs-toggle="tab" data-bs-target="#requirePaiement" type="button" role="tab" aria-controls="requirePaiement" aria-selected="true">Colis en attente de paiement</button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link tabLink" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">Colis payés en cours d'acheminement</button>
        </li>

      </ul>

      <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="requirePaiement" role="tabpanel" aria-labelledby="requirePaiement-tab">
            <table class="table border-secondary text-center fs-4">
              <thead>
                <tr>
                  <th scope="col">Mode de livraison</th>
                  <th scope="col">Destinataire</th>
                  <th scope="col">Code Postal</th>
                  <th scope="col">Référence</th>
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <?php require_once('include/connexionbdd.php');

                $bdd = connexionBDD();

                $req = $bdd->prepare('SELECT * FROM COLIS WHERE entreprise = ? AND status = "En attente du partenaire"');
                $req->execute([$_SESSION['siret']]);

                while($parcel = $req->fetch()){
                  $query = $bdd->prepare('SELECT * FROM CLIENT WHERE id = ?');
                  $query->execute([$parcel['client']]);
                  $client = $query->fetch();
                  echo
                  '<tr id="'.$parcel['id'].'">
                    <td>'.$parcel['modeLivraison'].'</td>
                    <td>'.$client['nom'].' '.$client['prenom'].'</td>
                    <td>'.$client['codePostal'].'</td>
                    <td>'.$parcel['refQrcode'].'</td>
                    <td><button type="button" name="button" class="btn btnTable" onclick="showMore(\''.$parcel['id'].'\')">Détails</button>
                    <button type="button" name="button" class="btn btnTable ms-5" onclick="delParcel(\''.$parcel['id'].'\',\''.$parcel['client'].'\')">Supprimer</button></td>
                  </tr>';
                }

                 ?>
              </tbody>
            </table>
        </div>
        <div class="tab-pane " id="paid" role="tabpanel" aria-labelledby="paid-tab">
          <table class="table border-secondary text-center fs-4">
            <thead>
              <tr>
                <th scope="col">Mode de livraison</th>
                <th scope="col">Destinataire</th>
                <th scope="col">Code Postal</th>
                <th scope="col">Référence</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php require_once('include/connexionbdd.php');

              $bdd = connexionBDD();

              $req = $bdd->prepare('SELECT * FROM COLIS WHERE entreprise = ? AND status != "En attente du partenaire" AND status != "Délivré"');
              $req->execute([$_SESSION['siret']]);

              while($parcel = $req->fetch()){
                $query = $bdd->prepare('SELECT * FROM CLIENT WHERE id = ?');
                $query->execute([$parcel['client']]);
                $client = $query->fetch();
                echo
                '<tr>
                  <td>'.$parcel['modeLivraison'].'</td>
                  <td>'.$client['nom'].' '.$client['prenom'].'</td>
                  <td>'.$client['codePostal'].'</td>
                  <td>'.$parcel['refQrcode'].'</td>
                  <td><button type="button" name="button" class="btn btnTable" onclick="showMore(\''.$parcel['id'].'\')">Détails</button></td>
                </tr>';
              }
               ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </body>
</html>
