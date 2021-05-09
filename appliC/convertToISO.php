<?php
require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$idList = $_POST['idList'];
$idArray = explode("_",$idList);

$count = 0;
for ($i=0; $i < count($idArray); $i++) {
  $query = $bdd->prepare("SELECT adresse,nom,prenom,ville FROM CLIENT WHERE id = ?");
  $query->execute([$idArray[$count]]);
  $client = $query->fetch();

  $query2 = $bdd->prepare("UPDATE CLIENT SET adresse = ?, nom = ?, prenom = ?,ville=? WHERE id = ?");
  $query2->execute([utf8_decode($client['adresse']),utf8_decode($client['nom']),utf8_decode($client['prenom']),utf8_decode($client['ville']),$idArray[$count]]);

  $count+=1;
}
 ?>
