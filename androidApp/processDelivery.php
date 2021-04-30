<?php
  require_once('../include/connexionbdd.php');

  $bdd = connexionBDD();

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  function curlRequest($url){
    $APIkey = 'ArW0cQI6DP5fIvTYTNLCR4pRfKPoxNfYQwvWOY3w6VCHSiNa0H2ECWUSeUO5g8rW';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,'http://dev.virtualearth.net/REST/V1/Routes/Driving?'.$url.'&optwp=true&optimize=timeWithTraffic&key='.$APIkey);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0');
    $result = curl_exec($ch);
    $data = json_decode($result);
    return $data;
  }

  function wpTime($url){

    //echo "URL : ".$url."<br>";

    $data = curlRequest($url);

    if($data->statusCode != 200){
      return 24*3600;
    }else{
      //echo "Temps : ".($data->resourceSets[0]->resources[0]->travelDurationTraffic/3600)."<br>";
      return ($data->resourceSets[0]->resources[0]->travelDurationTraffic);
    }
  }

  function distanceBetweenWp($url,$count){
    $data = curlRequest($url);

    global $jsonReturn;

    for ($i=0; $i < $count; $i++) {
      if($i == $count-1){
        $jsonReturn['end'][0]['distance'] =  round($data->resourceSets[0]->resources[0]->routeLegs[$i]->travelDistance);
      }else{
        $jsonReturn['colis'][$i]['distance'] =round($data->resourceSets[0]->resources[0]->routeLegs[$i]->travelDistance);
      }
    }
  }

  function getTimeDist($url){
    $data = curlRequest($url);

    global $jsonReturn;

    $jsonReturn['temps'] = $data->resourceSets[0]->resources[0]->travelDurationTraffic;
    $jsonReturn['distance'] =round($data->resourceSets[0]->resources[0]->travelDistance);
  }

  function sortWP($url){
    //echo "URL : ".$url."<br>";
    $data = curlRequest($url);

    global $jsonReturn;
    $tmpParcel;

    $jsonReturn['temps'] = $data->resourceSets[0]->resources[0]->travelDurationTraffic;
    $jsonReturn['distance'] =round($data->resourceSets[0]->resources[0]->travelDistance);

    $wpOrder= $data->resourceSets[0]->resources[0]->waypointsOrder;

    for ($i=1; $i < count($wpOrder)-1; $i++) {
      $wpNb = explode('.',$wpOrder[$i]);
      $tmpParcel[$i]=$jsonReturn['colis'][$wpNb[1]-1];
    }

    return array_values($tmpParcel);
  }

  $idDeposit = $_POST['deposit'];
  $delivererZone = $_POST['zone'];
  $time = $_POST['time'];
  $maxWeight=  $_POST['poids'];

  // $idDeposit = 71;
  // $delivererZone = 500;
  // $time = 3;
  // $maxWeight = 100;

  $queryDeposit = $bdd->prepare("SELECT adresse,ville,codePostal FROM DEPOT WHERE id = ?");
  $queryDeposit->execute([$idDeposit]);
  $depositData = $queryDeposit->fetch();

  $query = $bdd->prepare("SELECT colis.id,client.adresse,client.ville,client.codePostal,colis.refQrcode,colis.poids,client.nom,client.prenom,client.numPhone,client.info FROM COLIS INNER JOIN CLIENT ON colis.client = client.id
                          WHERE colis.distanceDepot <= ? AND colis.depot = ? AND colis.date = DATE(NOW()) AND colis.status = 'En attente de récupération par le livreur' AND colis.poids <= ? ORDER BY colis.distanceDepot DESC");

  $query->execute([$delivererZone,$idDeposit,$maxWeight]);

  $startAdresse= $depositData['adresse']." ".$depositData['ville']." ".$depositData['codePostal'];

  $urlWP="wp.0=".urlencode($startAdresse);

  $jsonReturn = array();

  $count=1;
  $countParcel = 0;
  $weight =0;

  $jsonReturn['start'][0]['idDepot'] = $idDeposit;
  $jsonReturn['start'][0]['adresse'] = $depositData['adresse'];
  $jsonReturn['start'][0]['ville'] = $depositData['ville'];
  $jsonReturn['start'][0]['codePostal'] = $depositData['codePostal'];

  $endAdresse = "";
  $wpOrder = array();

  while($parcel = $query->fetch()){
    if(($parcel['poids'] + $weight) <= $maxWeight){
      $parcelAdresse=$parcel['adresse']." ".$parcel['ville']." ".$parcel['codePostal'];
      $urlTmp = $urlWP."&wp.".$count."=".urlencode($parcelAdresse);

      if($endAdresse != ''){
        $urlTmp .="&wp.".($count+1)."=".urlencode($endAdresse);
      }

      if(wpTime($urlTmp) <= $time*3600){
        if($endAdresse == ''){
          $endAdresse = $parcelAdresse;
          $jsonReturn['end'][0]['idColis'] = $parcel['id'];
          $jsonReturn['end'][0]['refQrcode'] = $parcel['refQrcode'];
          $jsonReturn['end'][0]['adresse'] = $parcel['adresse'];
          $jsonReturn['end'][0]['ville'] = $parcel['ville'];
          $jsonReturn['end'][0]['codePostal'] = $parcel['codePostal'];
          $jsonReturn['end'][0]['nom'] = $parcel['nom'];
          $jsonReturn['end'][0]['prenom'] = $parcel['prenom'];
          $jsonReturn['end'][0]['numPhone'] = $parcel['numPhone'];
          $jsonReturn['end'][0]['info'] = $parcel['info'];
          $countParcel+=1;
        }else{
          $urlWP.="&wp.".$count."=".urlencode($parcelAdresse);
          $jsonReturn['colis'][$count-1]['id'] = $parcel['id'];
          $jsonReturn['colis'][$count-1]['refQrcode'] = $parcel['refQrcode'];
          $jsonReturn['colis'][$count-1]['adresse'] = $parcel['adresse'];
          $jsonReturn['colis'][$count-1]['ville'] = $parcel['ville'];
          $jsonReturn['colis'][$count-1]['codePostal'] = $parcel['codePostal'];
          $jsonReturn['colis'][$count-1]['nom'] = $parcel['nom'];
          $jsonReturn['colis'][$count-1]['prenom'] = $parcel['prenom'];
          $jsonReturn['colis'][$count-1]['numPhone'] = $parcel['numPhone'];
          $jsonReturn['colis'][$count-1]['info'] = $parcel['info'];
          $count+=1;
          $countParcel+=1;
        }
        $weight += $parcel['poids'];
      }
    }
  }

  $urlWP.="&wp.".$count."=".urlencode($endAdresse);

  if($countParcel > 2 ){
    $jsonReturn['colis']=sortWP($urlWP);
  }elseif($countParcel >0){
    getTimeDist($urlWP);
  }

  $jsonReturn['poids'] = $weight;
  $jsonReturn['nbColis'] = $countParcel;

  distanceBetweenWp($urlWP,$countParcel);

  print_r(json_encode($jsonReturn));

 ?>
