<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$ref = $_POST['ref'];
$name = $_POST['name'];

$req = $bdd->prepare('SELECT refQrcode,client,id,status,date FROM COLIS WHERE refQrcode = ?');
$req->execute([$ref]);
$parcel = $req->fetch();

if(empty($parcel)){
  echo '<h1 class="mt-5 error text-center">Vérifiez les information saisies</h1>';
}else{
  $clientid = $parcel['client'];

  $req = $bdd->prepare('SELECT nom,ville,codePostal FROM CLIENT WHERE id = ?');
  $req->execute([$clientid]);
  $client = $req->fetch();

  if(empty($client)){
    echo '<h1 class="mt-5 error text-center">Problème avec votre colis, merci de contacter le support</h1>';
  }else {
    if(strcasecmp($client['nom'], $name) == 0){

      echo '
        <div class="result mt-5 col-7 mx-auto d-flex p-3">
          <img src="asset/package.png" class="packageimg p-4">
          <div class="my-auto ps-5 textParcel">
            <h5 class="p-1">Nom du destinataire : '.$client['nom'].'</h5>
            <h5 class="p-1">Référence du colis : '.$parcel['refQrcode'].'</h5>
            <h5 class="p-1">Ville de destination : '.$client['codePostal'].', '.$client['ville'].'</h5>
            <h5 class="p-1">Status du colis : '.$parcel['status'].'</h5>
            <h5 class="p-1">Livraison prévue le : '.date('d/m/y', strtotime($parcel['date'])).'</h5>
          </div>
        </div>
      ';
    }else{
      echo '<h1 class="mt-5 error text-center">Vérifiez les information saisies</h1>';
    }
  }
}

 ?>
