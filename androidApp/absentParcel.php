<?php

require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$date = date('Y-m-d', strtotime("+1 day"));

$parcelId = $_POST['parcelId'];
$deliveryId = $_POST['deliveryId'];
$distance=$_POST['distance'];

$nbAbsence=$bdd->prepare("SELECT nbAbsence FROM COLIS WHERE id =?");
$nbAbsence->execute([$parcelId]);
$result=$nbAbsence->fetch();

$updAbsent=$result['nbAbsence']+1;

$query = $bdd->prepare("UPDATE contient SET status = 'Absent',distance=?,modifStatus=NOW(),nbAbsence=? WHERE colis = ? AND livraison = ?");
$query->execute([$distance,$updAbsent,$parcelId,$deliveryId]);

if($updAbsent == 3){
  $query = $bdd->prepare("UPDATE colis SET status = 'Retour à l''entreprise d''origine',date = ? WHERE id = ?");
  $query->execute([$date,$parcelId]);
}else{
  $query = $bdd->prepare("UPDATE colis SET status = 'Retour au dépot',date = ? WHERE id = ?");
  $query->execute([$date,$parcelId]);
}


 ?>
