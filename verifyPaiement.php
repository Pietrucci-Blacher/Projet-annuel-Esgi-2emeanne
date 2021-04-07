<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('include/connexionbdd.php');
require_once('stripe/init.php');

$bdd = connexionBDD();

$siret = $_SESSION['siret'];
$stripeToken = $_POST['stripeToken'];

$totalPrice = 0;

$queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non'");
$queryParcel->execute([$_SESSION['siret']]);

while($parcel = $queryParcel->fetch()){
  $totalPrice += $parcel['prix'];
}

$totalPrice = $totalPrice*100;

\Stripe\Stripe::setApiKey('sk_test_51IOoGkAympjcdUis1yNK8hgtyqz2OHGCM6s6SOJJTydkSotJ7pEI6d489f9dAzBquxNLoGJPucd2pAXEBHCrrMsL00lf3xzb7g');

$charge = \Stripe\Charge::create([
  'amount' => $totalPrice,
  'currency' => 'eur',
  'source' => $stripeToken,
]);


$encoded = json_encode($charge);
$decoded = json_decode($encoded);

echo $decoded->status;

 ?>
