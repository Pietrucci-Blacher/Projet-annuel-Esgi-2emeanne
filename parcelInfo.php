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

if($parcel['prix'] != ''){
  $return = $return . '<p class="h5">Prix de la livraison : '.$parcel['prix'].' €</p>
                      <p class="h5">Status : '.$parcel['status'].'</p>
                      <p class="h5">Date de livraison prévue : '.$parcel['date'].'</p>';
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
