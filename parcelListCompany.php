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
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <script>
      function showMore(weight,speed,surname,firstname,address,city,zip,phone,info,price,date,status){
        document.getElementById('weight').innerHTML = 'Poids : ' + weight + ' kg';
        if(price == '' && date == ''){
            document.getElementById('price').innerHTML = '';
            document.getElementById('date').innerHTML = '';
            document.getElementById('status').innerHTML = '';
        }else{
          document.getElementById('price').innerHTML = 'Prix de la liraison : ' + price + ' €';
          document.getElementById('date').innerHTML = 'Date de livraison prévue : ' + date;
          document.getElementById('status').innerHTML = 'Etat du colis : ' + status;
        }
        document.getElementById('speed').innerHTML = 'Mode de livraison : ' + speed;
        document.getElementById('surname').innerHTML = 'Nom du destinataire : ' + surname + ' ' + firstname;
        document.getElementById('address').innerHTML = 'Adresse : ' + address;
        document.getElementById('city').innerHTML = 'Ville : ' + city + ' ' + zip;
        if(phone != ''){
          document.getElementById('phone').innerHTML = 'Numéro de téléphone : ' + phone;
        }
        if(info != ''){
          document.getElementById('info').innerHTML = 'Informations supplémentaire : ' + info;
        }
        $(function () {
           $('#exampleModal').modal('toggle');
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
          <div class="modal-body">
            <p class="h5" id="weight"></p>
            <p class="h5" id="speed"></p>
            <p class="h5" id="price"></p>
            <p class="h5" id="date"></p>
            <p class="h5" id="status"></p>
            <p class="h5" id="surname"></p>
            <p class="h5" id="address"></p>
            <p class="h5" id="city"></p>
            <p class="h5" id="phone"></p>
            <p class="h5" id="info"></p>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-5">
      <h1 class="banner-item text-center mb-5">Liste des colis</h1>

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
                  <th scope="col" colspan="2"></th>
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
                    <td><button type="button" name="button" class="btn btn-primary" onclick="showMore('.$parcel['poids'].',\''.$parcel['modeLivraison'].'\',\''.$client['nom'].'\',\''.$client['prenom'].'\',\''.$client['adresse'].'\',\''.$client['ville'].'\',\''.$client['codePostal'].
                    '\',\''.$client['numPhone'].'\',\''.$client['info'].'\',\''.$parcel['prix'].'\',\''.$parcel['date'].'\',\''.$parcel['status'].'\')">Détails</button>
                    <button type="button" name="button" class="btn btn-primary ms-5" onclick="delParcel(\''.$parcel['id'].'\',\''.$parcel['client'].'\')">Supprimer</button></td>
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
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php require_once('include/connexionbdd.php');

              $bdd = connexionBDD();

              $req = $bdd->prepare('SELECT * FROM COLIS WHERE entreprise = ? AND status != "En attente du partenaire" AND status != "Payé"');
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
                  <td><button type="button" name="button" class="btn btn-primary" onclick="showMore('.$parcel['poids'].',\''.$parcel['modeLivraison'].'\',\''.$client['nom'].'\',\''.$client['prenom'].'\',\''.$client['adresse'].'\',\''.$client['ville'].'\',\''.$client['codePostal'].
                  '\',\''.$client['numPhone'].'\',\''.$client['info'].'\',\''.$parcel['prix'].'\',\''.$parcel['date'].'\',\''.$parcel['status'].'\')">Détails</button></td>
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
