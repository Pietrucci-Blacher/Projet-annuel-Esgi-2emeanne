<?php
require_once('../include/connexionbdd.php');

  $bdd = connexionBDD();

  $email = $_POST['email'];

  $mdp = isset($_POST['mdp']) ? sha1(htmlspecialchars($_POST['mdp'])) : '';

  $query = "SELECT nom,prenom,status FROM CLIENT WHERE email = ? AND mdp = ?";

  $connect=$bdd->prepare($query);

  $connect->execute([$email,$mdp]);

  $success = $connect->fetch();

  if (empty($success)) {
    echo "failed";
  }else{
    if($success['status'] != 'livreur'){
      echo 'failed';
    }else{
      echo $success['prenom'].' '.$success['nom'];
    }
  }

?>
