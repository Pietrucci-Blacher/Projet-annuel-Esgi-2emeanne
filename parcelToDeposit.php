<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <?php
    require_once('include/connexionbdd.php');

    $bdd = connexionBDD();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $query = $bdd->prepare("SELECT id,adresse,codePostal FROM DEPOT");
    $query->execute();

    $depositData = array();
    $parcelData = array();
    $ch = curl_init();

    $count = 0;
    while($deposit = $query->fetch()){
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_URL,'https://api-adresse.data.gouv.fr/search/?q='.urlencode(utf8_decode($deposit['adresse'])).'&postcode='.urlencode(utf8_decode($deposit['codePostal'])));
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
      curl_setopt($ch, CURLOPT_URL,'https://api-adresse.data.gouv.fr/search/?q='.urlencode(utf8_decode($parcel['adresse'])).'&postcode='.urlencode(utf8_decode($parcel['codePostal'])));
      curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0');
      $result = curl_exec($ch);

      $data = json_decode($result);
      // print_r($result);
      $parcelData[$count]['idDeposit']=$parcel['id'];
      $parcelData[$count]['longitude']=$data->features[0]->geometry->coordinates[0];
      $parcelData[$count]['lattitude']=$data->features[0]->geometry->coordinates[1];
      $count += 1;
    }

    print_r($parcelData);
     ?>
  </body>
</html>
