<?php

require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$idDeliver = $_POST['idDeliver'];
$parcelsId = $_POST['parcelsId'];

$parcels = explode(".",$parcelsId);

$query = $bdd->prepare("INSERT INTO livraison(date,livreur,status) VALUES (NOW(),?,'En cours')");
$query->execute([$idDeliver]);
$idDelivery = $bdd->lastInsertId();

for ($i=0; $i < count($parcels) ; $i++) {
  $query= $bdd->prepare("INSERT INTO contient(status,colis,livraison) VALUES ('Récupéré',?,?)");
  $query->execute([$parcels[$i],$idDelivery]);
}

for ($i=0; $i < count($parcels) ; $i++) {
  $query= $bdd->prepare("UPDATE colis SET status = 'En cours de livraison' WHERE id = ?");
  $query->execute([$parcels[$i]]);
}

echo "success";
 ?>
