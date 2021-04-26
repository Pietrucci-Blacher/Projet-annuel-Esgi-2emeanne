<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$search = $_POST['search'];
$searchBy = $_POST['searchBy'];
$speed = $_POST['speed'];
$paiement = $_POST['paiement'];
$siret = $_SESSION['siret'];


$query = "SELECT client.nom,client.prenom,colis.modeLivraison,client.codePostal,colis.refQrcode,colis.id,colis.client,colis.statusPaiement,colis.status FROM colis INNER JOIN client ON colis.client = client.id WHERE colis.entreprise = ?";
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
    $query .= " AND colis.statusPaiement = 'oui'";
}elseif ($paiement=='2'){
    $query .= " AND colis.statusPaiement = 'non'";
}

$query .= " ORDER BY colis.statusPaiement";

$data = $bdd->prepare($query);
$data->execute($params);

while($parcel = $data->fetch()){
    echo '
    <tbody><tr>
      <td>'.$parcel['modeLivraison'].'</td>
      <td>'.$parcel['nom'].' '.$parcel['prenom'].'</td>
      <td>'.$parcel['codePostal'].'</td>
      <td>'.$parcel['refQrcode'].'</td>
      <td><button type="button" name="button" class="btn btnTable" onclick="showMore(\''.$parcel['id'].'\')">Détails</button></td>
    </tr></tbody>';
}


 ?>
