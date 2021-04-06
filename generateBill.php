<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$siret = $_SESSION['siret'];

$totalPrice = 0;
$totalParcel = 0;



$query = $bdd->prepare("INSERT INTO facture(date,entreprise) VALUES (NOW(),?) ");
$query->execute([$siret]);
$billId = $bdd->lastInsertId();

$queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non'");
$queryParcel->execute([$siret]);

while($parcel=$queryParcel->fetch()){
  $insertParcel=$bdd->prepare("UPDATE colis SET facture = ?,statusPaiement = ? WHERE id = ?");
  $insertParcel->execute([$billId,'oui',$parcel['id']]);
  $totalPrice += $parcel['prix'];
  $totalParcel += 1;
}

$queryBill = $bdd->prepare("UPDATE facture SET montant = ?, nbColis = ? WHERE id = ?");
$queryBill->execute([$totalPrice,$totalParcel,$billId]);


 ?>
