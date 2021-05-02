<?php
require_once('../include/connexionbdd.php');

$bdd = connexionBDD();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$deliveryId = $_POST['deliveryId'];
$nbKm = $_POST['nbKm'];

$query = $bdd->prepare("UPDATE livraison SET status = 'Terminée',nbKm = ? WHERE id = ?");
$query->execute([$nbKm,$deliveryId]);


 ?>
