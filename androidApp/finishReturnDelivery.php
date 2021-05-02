<?php
require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deliveryId = $_POST['deliveryId'];
$nbKm = $_POST['nbKm'];

$query = $bdd->prepare("UPDATE livraison SET status = 'Terminée',nbKm = ? WHERE id = ?");
$query->execute([$nbKm,$deliveryId]);

$parcel = $bdd->prepare("SELECT colis FROM contient WHERE livraison = ? AND (status = 'Absent' OR status = 'Annulé')");
$parcel->execute([$deliveryId]);

while($parcelId = $parcel->fetch()){
  $changeStatus = $bdd->prepare("UPDATE colis SET status = 'En attente de récupération par le livreur' WHERE id = ?");
  $changeStatus->execute([$parcelId['colis']]);
}

 ?>
