<?php

require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//$idDeliver= $_POST['$idDeliver'];
$idDeliver=1;

$query=$bdd->prepare("SELECT COUNT(id) as nbSalary,date FROM SALAIRE WHERE livreur = ? ORDER BY date DESC");
$query->execute([$idDeliver]);
$salary=$query->fetch();

if($salary['nbSalary']==0){
  $delivery=$bdd->prepare("SELECT * FROM livraison WHERE date < NOW() AND livreur = ? AND status = 'Terminée'");
  $delivery->execute([$idDeliver]);
}else{
  $delivery=$bdd->prepare("SELECT * FROM livraison WHERE date > ? AND livreur = ? AND status = 'Terminée'");
  $delivery->execute([$salary['date'],$idDeliver]);
}

$nbKM = 0;
$affectedParcel=0;
$deliveredParcel=0;
$json=array();
$count = 0;

while($deliveryInfo=$delivery->fetch()){
  $nbKM+=$deliveryInfo['nbKm'];

  $parcel=$bdd->prepare("SELECT contient.status,colis.poids FROM contient INNER JOIN colis ON contient.colis = colis.id WHERE contient.livraison = ?");
  $parcel->execute([$deliveryInfo['id']]);
  while($parcelInfo=$parcel->fetch()){
    $affectedParcel+=1;
    if($parcelInfo['status']!='Annulé'){
      $deliveredParcel+=1;
      if($parcelInfo['poids']>30){
        $json['sup30'][$count]=$parcelInfo['poids'];
        $count+=1;
      }
    }
  }
}

$json['parcelSup30']=$count;
$json['nbKm']=$nbKM;
$json['affected']=$affectedParcel;
$json['delivered']=$deliveredParcel;

echo json_encode($json);
 ?>
