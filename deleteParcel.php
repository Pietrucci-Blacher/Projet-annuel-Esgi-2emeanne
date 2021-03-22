<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$idParcel = $_POST['idParcel'];
$idClient = $_POST['idClient'];


$req = $bdd->prepare('DELETE FROM COLIS WHERE id = ?');
$success = $req->execute([$idParcel]);

if($success){
  $affected = $req->rowcount();
}

if($affected == 1){
  $req = $bdd->prepare('DELETE FROM CLIENT WHERE id = ?');
  $success = $req->execute([$idClient]);
  $affected = $req->rowcount();
}

if($affected == 1){
  echo 'success';
}else{
  echo 'fail';
}
