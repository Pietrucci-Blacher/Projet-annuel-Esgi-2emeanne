<?php

require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$parcelId = $_POST['parcelId'];
$deliveryId = $_POST['deliveryId'];

$query = $bdd->prepare("UPDATE contient SET status = 'Délivré' WHERE colis = ? AND livraison = ?");
$query->execute([$parcelId,$deliveryId]);

$query = $bdd->prepare("UPDATE colis SET status = 'Délivré au destinataire' WHERE id = ?");
$query->execute([$parcelId]);

 ?>