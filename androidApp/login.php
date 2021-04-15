<?php
require_once('../include/connexionbdd.php');

  $bdd = connexionBDD();

  $email = $_POST['email'];

  $mdp = isset($_POST['mdp']) ? sha1(htmlspecialchars($_POST['mdp'])) : '';

  $query = "SELECT client.nom,client.prenom,livreur.id FROM CLIENT INNER JOIN LIVREUR ON livreur.client = client.id WHERE client.email = ? AND client.mdp = ?";

  echo $query;

?>
