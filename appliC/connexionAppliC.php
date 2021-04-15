<?php
require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

$id = $_POST['id'];

$mdp = isset($_POST['mdp']) ? sha1(htmlspecialchars($_POST['mdp'])) : '';

$query = 'SELECT numSiret,client FROM ENTREPRISE WHERE numSiret = ?';
$req = $bdd->prepare($query);
$req->execute([$id]);
$success = $req->fetch();

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
