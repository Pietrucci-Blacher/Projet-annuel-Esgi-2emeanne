<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$search = $_POST['search'];
$searchBy = $_POST['searchBy'];
$speed = $_POST['speed'];
$paiement = $_POST['paiement'];
$siret = $_SESSION['siret'];


$query = "SELECT client.nom,client.prenom,colis.modeLivraison,client.codePostal,colis.refQrcode,colis.id,colis.client,colis.status FROM colis INNER JOIN client ON colis.client = client.id WHERE colis.entreprise = ?";
$params[] = $siret;

if($searchBy == '1'){
  $query .= " AND client.nom LIKE ?";
  array_push($params,"%" .$search. "%");
}elseif ($searchBy == '2'){
  $query .= " AND client.codePostal LIKE ?";
  array_push($params,"%" .$search. "%");
}elseif ($searchBy == '3' ){
  $query .= " AND colis.refQrcode LIKE ?";
  array_push($params,"%" .$search. "%");
}

if ($speed =='1') {
  $query .= " AND colis.modeLivraison = 'standard'";
}elseif ($speed=='2'){
  $query .= " AND colis.modeLivraison = 'express'";
}

if ($paiement =='1') {
    $query .= " AND colis.status != 'En attente du partenaire'";
}elseif ($paiement=='2'){
    $query .= "AND colis.status = 'En attente du partenaire'";
}

$data = $bdd->prepare($query);
$data->execute($params);

while($parcel = $data->fetch()){
  if($parcel['status'] == 'En attente du partenaire'){
    echo
    '<tbody><tr id="'.$parcel['id'].'">
      <td>'.$parcel['modeLivraison'].'</td>
      <td>'.$parcel['nom'].' '.$parcel['prenom'].'</td>
      <td>'.$parcel['codePostal'].'</td>
      <td>'.$parcel['refQrcode'].'</td>
      <td><button type="button" name="button" class="btn btnTable" onclick="showMore(\''.$parcel['id'].'\')">Détails</button>
      <button type="button" name="button" class="btn btnTable ms-5" onclick="delParcel(\''.$parcel['id'].'\',\''.$parcel['client'].'\')">Supprimer</button></td>
    </tr></tbody>';
  }elseif($parcel['status'] != 'En attente du partenaire' && $parcel['status'] != 'Délivré'){
    echo '
    <tbody><tr>
      <td>'.$parcel['modeLivraison'].'</td>
      <td>'.$parcel['nom'].' '.$parcel['prenom'].'</td>
      <td>'.$parcel['codePostal'].'</td>
      <td>'.$parcel['refQrcode'].'</td>
      <td><button type="button" name="button" class="btn btnTable" onclick="showMore(\''.$parcel['id'].'\')">Détails</button></td>
    </tr></tbody>';
  }
}


 ?>
