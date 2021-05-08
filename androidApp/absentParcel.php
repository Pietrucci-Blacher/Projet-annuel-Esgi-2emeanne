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

$query = $bdd->prepare("UPDATE contient SET status = 'Absent',distance=?,modifStatus=NOW() WHERE colis = ? AND livraison = ?");
$query->execute([$distance,$parcelId,$deliveryId]);

$query = $bdd->prepare("UPDATE colis SET status = 'Retour au dépot',date = ? WHERE id = ?");
$query->execute([$date,$parcelId]);

 ?>
