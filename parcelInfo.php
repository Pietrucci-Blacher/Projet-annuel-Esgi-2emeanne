<?php require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$idParcel = $_POST['idParcel'];

$req = $bdd->prepare('SELECT * FROM COLIS WHERE id = ?');
$req->execute([$idParcel]);
$parcel = $req->fetch();

$query = $bdd->prepare('SELECT * FROM CLIENT WHERE id = ?');
$query->execute([$parcel['client']]);
$client = $query->fetch();

$return =  '<p class="h5">Référence : '.$parcel['refQrcode'].'</p>
  <p class="h5">Poids : '.$parcel['poids'].' kg</p>
  <p class="h5">Mode de livraison : '.$parcel['modeLivraison'].'</p>';

if($parcel['status'] != 'En attente du partenaire'){
  $return = $return . '<p class="h5">Prix de la livraison : '.$parcel['prix'].' €</p>
                      <p class="h5">Status : '.$parcel['status'].'</p>
                      <p class="h5">Date de livraison prévue : '.$parcel['date'].'</p>';
}elseif($parcel['status'] == 'En attente du partenaire'){
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

  function calculatePrice($weight,$priceParcelList){
    global $weightParcel;
    global $totalPrice;
    global $totalParcel;

    for ($i=0; $i < sizeof($weightParcel) ; $i++) {
      if ($weight<=$weightParcel[$i]) {
        $priceParcel = $priceParcelList[$i];
        return $priceParcel;
      }else if($weight>$weightParcel[8]){
        $priceParcel=$weight%20*$priceParcelList[9];
        return $priceParcel;
      }
    }
  }
  if($parcel['modeLivraison'] == 'express'){
    $return = $return . '<p class="h5">Prix estimé de la livraison : '.calculatePrice($parcel['poids'],$priceParcelExpress).' €</p>';
  }elseif($parcel['modeLivraison'] == 'standard'){
    $return = $return . '<p class="h5">Prix estimé de la livraison : '.calculatePrice($parcel['poids'],$priceParcelStandard).' €</p>';
  }
}

$return = $return .
'<p class="h5">Nom du destinataire : '.$client['nom'].' '.$client['prenom'].'</p>
<p class="h5">Adresse : '.$client['adresse'].'</p>
<p class="h5">Ville : '.$client['ville'].' '.$client['codePostal'].'</p>';

if($client['numPhone'] != ''){
  $return = $return . '<p class="h5">Numéro de télépone : '.$client['numPhone'].'</p>';
}

if($client['info'] != ''){
  $return = $return . '<p class="h5">Informations supplémentaires : '.$client['info'].'</p>';
}


echo $return;


 ?>
