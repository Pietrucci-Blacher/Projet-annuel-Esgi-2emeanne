<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$priceExp=$_POST['priceE'];
$priceStd=$_POST['priceS'];
$weight = $_POST['weight'];

$query=$bdd->prepare("INSERT INTO tarifcolis (prixStandard,prixExpress,poidsMax,date) VALUES (?,?,?,NOW())");
$query->execute([$priceStd,$priceExp,$weight]);
 ?>
