<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$siret = $_SESSION['siret'];

$today = date("d.m.y");

$priceParcelExpress =[];
$priceParcelStandard=[];
$weightParcel = [];
$queryPrice= $bdd->prepare("SELECT * FROM tarifcolis ORDER BY id");
$queryPrice->execute();

$count = 0;
while ($price = $queryPrice->fetch()) {
  array_push($priceParcelExpress,$price['prixExpress']);
  array_push($priceParcelStandard,$price['prixStandard']);
  $count+=1;
  if($count < 10){
    array_push($weightParcel,$price['poidsMax']);
  }
}

$totalPrice = 0;
$totalParcel = 0;
function calculatePrice($weight,$priceParcellist){
  global $weightParcel;
  global $totalPrice;
  global $totalParcel;

  for ($i=0; $i < sizeof($weightParcel) ; $i++) {
    if ($weight<=$weightParcel[$i]) {
      $priceParcel = $priceParcelList[$i];
      $totalPrice += $priceParcel;
      $totalParcel += 1;
      return $priceParcel;
    }else if($weight>$weightParcel[8]){
      $priceParcel=$weight%20*$priceParcelList[9];
      $totalPrice +=$priceParcel;
      $totalParcel += 1;
      return $priceParcel;
    }
  }
}

$query = $bdd->prepare("INSERT INTO facture(date,entreprise) VALUES (?,?) ");
$query->execute([$today,$siret]);
$billId = $bdd->lastInsertId();

$queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND status = 'En attente du partenaire'");
$queryParcel->execute([$siret]);

while($parcel=$queryParcel->fetch()){
  if($parcel['modeLivraison'] == 'express'){
    $parcelPrice = calculatePrice($parcel['poids'],$priceParcelExpress);
    $date = mktime(0, 0, 0, date("d")+2, date("m"), date("Y"));
  }elseif($parcel['modeLivraison'] == 'standard'){
    $parcelPrice = calculatePrice($parcel['poids'],$priceParcelStandard);
    $date = mktime(0, 0, 0, date("d")+5, date("m"), date("Y"));
  }
  $insertParcel=$bdd->prepare("UPDATE colis SET prix = ?, status = 'En attente de récupération par le livreur', facture = ?, date = ? WHERE id = ?");
  $insertParcel->execute([$parcelPrice,$billId,$date,$parcel['id']]);
}

$queryBill = $bdd->prepare("UPDATE facture SET montant = ?, nbColis = ? WHERE id = ?");
$queryBill->execute([$totalPrice,$totalParcel,$billId]);


 ?>
