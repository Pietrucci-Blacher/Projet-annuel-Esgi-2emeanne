<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$query=$bdd->prepare("SELECT id FROM colis WHERE date<DATE(NOW())");
$query->execute();

while ($parcel = $query->fetch()) {
  $updateParcel=$bdd->prepare("UPDATE colis SET date=DATE(NOW()) WHERE id = ?");
  $updateParcel->execute([$parcel['id']]);
}
 ?>
