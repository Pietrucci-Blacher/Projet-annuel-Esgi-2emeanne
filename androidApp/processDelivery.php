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
    $data = curlRequest($url);
    if($data->statusCode != 200){
      return 24*3600;
    }else{
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
    $jsonReturn['distance'] +=round($data->resourceSets[0]->resources[0]->travelDistance);
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

  function filljson($category,$count,$id,$ref,$addr,$city,$zip,$lname,$fname,$phone,$info){
    global $jsonReturn;

    $jsonReturn[$category][$count]['idColis'] = $id;
    $jsonReturn[$category][$count]['refQrcode'] = $ref;
    $jsonReturn[$category][$count]['adresse'] = $addr;
    $jsonReturn[$category][$count]['ville'] = $city;
    $jsonReturn[$category][$count]['codePostal'] = $zip;
    $jsonReturn[$category][$count]['nom'] = $lname;
    $jsonReturn[$category][$count]['prenom'] = $fname;
    $jsonReturn[$category][$count]['numPhone'] = $phone;
    $jsonReturn[$category][$count]['info'] = $info;
  }

  $jsonReturn = array();
  $idDeposit = $_POST['deposit'];
  $delivererZone = $_POST['zone'];
  $time = $_POST['time'];
  $maxWeight=  $_POST['poids'];

  $queryDeposit = $bdd->prepare("SELECT adresse,ville,codePostal FROM DEPOT WHERE id = ?");
  $queryDeposit->execute([$idDeposit]);
  $depositData = $queryDeposit->fetch();

  $idDelivery = $_POST['idDelivery'];

  $distanceToAdd=0;
  $startAdresse="";

  if($idDelivery=='none'){
    $query = $bdd->prepare("SELECT colis.id,client.adresse,client.ville,client.codePostal,colis.refQrcode,colis.poids,client.nom,client.prenom,client.numPhone,client.info FROM COLIS INNER JOIN CLIENT ON colis.client = client.id
                           WHERE colis.distanceDepot <= ? AND colis.depot = ? AND colis.date=DATE(NOW()) AND colis.status = 'En attente de récupération par le livreur' AND colis.poids <= ? ORDER BY colis.distanceDepot DESC");
    $query->execute([$delivererZone,$idDeposit,$maxWeight]);
    $jsonReturn['countReturn']=0;
  }else{
    $query=$bdd->prepare("SELECT colis.id,client.adresse,client.ville,client.codePostal,colis.refQrcode,colis.poids,client.nom,client.prenom,client.numPhone,client.info FROM COLIS INNER JOIN CLIENT ON colis.client = client.id
                          JOIN contient ON contient.colis = colis.id WHERE contient.livraison = ? AND contient.status='Récupéré' ORDER BY colis.distanceDepot DESC");
    $query->execute([$idDelivery]);

    $return=$bdd->prepare("SELECT colis.refQrcode FROM COLIS JOIN CONTIENT ON colis.id=contient.colis WHERE (contient.status = 'Absent' OR contient.status = 'Annulé') AND contient.livraison=?");
    $return->execute([$idDelivery]);

    $countReturn = 0;

    while($returnParcel = $return->fetch()){
      $jsonReturn['returnParcel'][$countReturn]['refQrcode']=$returnParcel['refQrcode'];
      $countReturn+=1;
    }
    $jsonReturn['countReturn']=$countReturn;

    $distanceQuery=$bdd->prepare("SELECT contient.distance,client.adresse,client.ville,client.codePostal FROM COLIS INNER JOIN CLIENT ON colis.client=client.id
      JOIN CONTIENT ON colis.id=contient.colis WHERE contient.livraison=? AND (contient.status = 'Absent' OR contient.status = 'Délivré') ORDER BY contient.modifStatus ASC");

    $distanceQuery->execute([$idDelivery]);
    while($distanceRes=$distanceQuery->fetch()){
      $startAdresse=$distanceRes['adresse']." ".$distanceRes['ville']." ".$distanceRes['codePostal'];
      $distanceToAdd+= $distanceRes['distance'];
    }
  }

  if($startAdresse==""){
    $startAdresse= $depositData['adresse']." ".$depositData['ville']." ".$depositData['codePostal'];
  }

  $urlWP="wp.0=".urlencode($startAdresse);

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
          filljson('end',0,$parcel['id'],$parcel['refQrcode'],$parcel['adresse'],$parcel['ville'],$parcel['codePostal'],$parcel['nom'],$parcel['prenom'],$parcel['numPhone'],$parcel['info']);
          $countParcel+=1;
        }else{
          $urlWP.="&wp.".$count."=".urlencode($parcelAdresse);
          filljson('colis',$count-1,$parcel['id'],$parcel['refQrcode'],$parcel['adresse'],$parcel['ville'],$parcel['codePostal'],$parcel['nom'],$parcel['prenom'],$parcel['numPhone'],$parcel['info']);
          $count+=1;
          $countParcel+=1;
        }
        $weight += $parcel['poids'];
      }
    }
  }

  $urlWP.="&wp.".$count."=".urlencode($endAdresse);

  $jsonReturn['distance']=0;

  if($countParcel > 2 ){
    $jsonReturn['colis']=sortWP($urlWP);
  }elseif($countParcel >0){
    getTimeDist($urlWP);
  }

  $jsonReturn['poids'] = $weight;
  $jsonReturn['nbColis'] = $countParcel;

  distanceBetweenWp($urlWP,$countParcel);

  $jsonReturn['distance']+=$distanceToAdd;

  print_r(json_encode($jsonReturn));

 ?>
