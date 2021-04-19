<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getDistanceFromLatLonInKm($lon1,$lat1,$lon2,$lat2) {
    $R = 6371; // Radius of the earth in km
    $dLat = deg2rad($lat2-$lat1);  // deg2rad below
    $dLon = deg2rad($lon2-$lon1);
    $a =
      sin($dLat/2) * sin($dLat/2) +
      cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
      sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $d = $R * $c; // Distance in km
    return $d;
  }


$query = $bdd->prepare("SELECT id,adresse,codePostal FROM DEPOT");
$query->execute();

$depositData = array();
$parcelData = array();
$ch = curl_init();

$count = 0;
while($deposit = $query->fetch()){
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_URL,'https://api-adresse.data.gouv.fr/search/?q='.urlencode($deposit['adresse']).'&postcode='.urlencode($deposit['codePostal']));
   curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0');
   $result = curl_exec($ch);
   $data = json_decode($result);

   $depositData[$count]['idDeposit']=$deposit['id'];
   $depositData[$count]['longitude']=$data->features[0]->geometry->coordinates[0];
   $depositData[$count]['lattitude']=$data->features[0]->geometry->coordinates[1];
   $count += 1;
}

$query = $bdd->prepare("SELECT client.adresse,client.codePostal,colis.id FROM COLIS INNER JOIN CLIENT ON colis.client = client.id WHERE colis.depot is NULL");
$query->execute();

$count = 0;
while($parcel = $query->fetch()){
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,'https://api-adresse.data.gouv.fr/search/?q='.urlencode($parcel['adresse']).'&postcode='.urlencode($parcel['codePostal']));
  curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0');
  $result = curl_exec($ch);

  $data = json_decode($result);
  // print_r($result);
  $parcelData[$count]['idParcel']=$parcel['id'];
  $parcelData[$count]['longitude']=$data->features[0]->geometry->coordinates[0];
  $parcelData[$count]['lattitude']=$data->features[0]->geometry->coordinates[1];
  $count += 1;
}

$parcelDeposit = array();
$min = 100000;

for ($i=0; $i < count($parcelData); $i++) {
  $parcelDeposit[$i]['idParcel'] = $parcelData[$i]['idParcel'];
  for ($j=0; $j < count($depositData); $j++) {
     $dist = getDistanceFromLatLonInKm($parcelData[$i]['longitude'],$parcelData[$i]['lattitude'],$depositData[$j]['longitude'],$depositData[$j]['lattitude']);
     if($dist<$min){
       $min = $dist;
       $parcelDeposit[$i]['idDeposit'] = $depositData[$j]['idDeposit'];
       $parcelDeposit[$i]['distance'] = round($min);
     }
  }
  $min = 100000;
}

for ($i=0; $i < count($parcelDeposit); $i++) {
  $query= $bdd->prepare("UPDATE COLIS SET depot = ?, distanceDepot = ? WHERE id = ?");
  $query->execute([$parcelDeposit[$i]['idDeposit'],$parcelDeposit[$i]['distance'],$parcelDeposit[$i]['idParcel']]);
}

 ?>
