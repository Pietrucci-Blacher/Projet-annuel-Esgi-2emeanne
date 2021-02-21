<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$id = $_POST['id'];
$mdp = $_POST['mdp'];

$query = 'SELECT numSiret,client FROM ENTREPRISE WHERE numSiret = ?';
$req = $bdd->prepare($query);
$req->execute([$id]);
$success = $req->fetch();

// !!! return code (echo) must be 7 caracters long
if(empty($success)){
  echo "1";
}else{
  $idClient = $success['client'];

  $query = 'SELECT id FROM CLIENT WHERE id = ? AND mdp = ?';
  $req = $bdd->prepare($query);
  $req->execute([$idClient,$mdp]);
  $success = $req->fetch();
  if (empty($success)) {
    echo "2";
  }else{
    echo "3";
  }
}


?>