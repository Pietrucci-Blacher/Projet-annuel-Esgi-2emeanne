<?php
require_once('../include/connexionbdd.php');

  $bdd = connexionBDD();

  $email = $_POST['email'];

  $mdp = isset($_POST['mdp']) ? sha1(htmlspecialchars($_POST['mdp'])) : '';

  $query = "SELECT client.nom,client.prenom,client.status,livreur.zoneGeo,livreur.ptacvehicule,livreur.depot,livreur.id FROM CLIENT INNER JOIN LIVREUR ON client.id = livreur.client WHERE client.email = ? AND client.mdp = ?";

  $connect=$bdd->prepare($query);

  $connect->execute([$email,$mdp]);

  $success = $connect->fetch();

  $obj = array();

  if (empty($success)) {
    $obj['status']= "failed";
  }else{
    if($success['status'] != 'livreur'){
      $obj['status']= "failed";
    }else{
      $queryDeliver=$bdd->prepare("SELECT id FROM livraison WHERE status = 'En cours' AND livreur=?");
      $queryDeliver->execute([$success['id']]);
      $deliver=$queryDeliver->fetch();

      if($queryDeliver->rowCount()==0){
        $obj['idLivraison']="none";
      }else{
        $obj['idLivraison']=$deliver['id'];
      }

      $obj['status']= "success";
      $obj['prenom']= $success['prenom'];
      $obj['nom']= $success['nom'];
      $obj['zoneGeo']= $success['zoneGeo'];
      $obj['poidsVehicule']= $success['ptacvehicule'];
      $obj['idDepot']= $success['depot'];
      $obj['idLivreur']= $success['id'];
    }
  }

  $obj = json_encode($obj);

  header("Content-Type: application/json");
  echo $obj;
?>
