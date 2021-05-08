<?php

require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deliveryId = $_POST['deliveryId'];

$query = $bdd->prepare("SELECT colis FROM CONTIENT WHERE livraison = ? AND status = 'Récupéré'");
$query->execute([$deliveryId]);

while($delivery=$query->fetch()){
  $contain = $bdd->prepare("UPDATE CONTIENT SET status = 'Annulé',distance=0 WHERE colis = ? AND livraison = ?");
  $contain->execute([$delivery['colis'],$deliveryId]);

  $parcel = $bdd->prepare("UPDATE colis SET status = 'Retour au dépot' WHERE id = ?");
  $parcel->execute([$delivery['colis']]);
}

 ?>
