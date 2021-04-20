<?php
require_once('../include/connexionbdd.php');

  $bdd = connexionBDD();

  $email = $_POST['email'];

  $mdp = isset($_POST['mdp']) ? sha1(htmlspecialchars($_POST['mdp'])) : '';

  $query = "SELECT nom,prenom,status FROM CLIENT WHERE email = ? AND mdp = ?";

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
      $obj['status']= "success";
      $obj['prenom']= $success['prenom'];
      $obj['nom']= $success['nom'];
    }
  }

  $obj = json_encode($obj);

  header("Content-Type: application/json");
  echo $obj;
?>
