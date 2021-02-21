<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$id = $_POST['id'];
$mdp = $_POST['mdp'];

$query = 'SELECT numSiret,client FROM ENTREPRISE WHERE numSiret = ?';
$req = $bdd->prepare($query);
$req->execute([$id]);
$success = $req->fetch();

if(empty($success)){
  echo "errorId";
}else{
  $idClient = $success['cient'];

  $query = 'SELECT id FROM CLIENT WHERE id = ? AND mdp = ?';
  $req = $bdd->prepare($query);
  $req->execute([$idClient,$mdp]);
  $success = $req->fetch();
  if (empty($success)) {
    echo "errorMdp";
  }else{
    echo "success";
  }
}


?>
