<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../include/connexionbdd.php');
require_once('../stripe/init.php');
require_once('stripeFunction.php');

$bdd = connexionBDD();

$stripe = new \Stripe\StripeClient(
  'sk_test_51IOoGkAympjcdUis1yNK8hgtyqz2OHGCM6s6SOJJTydkSotJ7pEI6d489f9dAzBquxNLoGJPucd2pAXEBHCrrMsL00lf3xzb7g'
);

$idDeliver=$_POST['idDeliver'];
$amount=$_POST['amount'];
$bankAccount=$_POST['bankAccount'];
$primeObjectif=$_POST['primeObjectif'];
$nbParcel=$_POST['nbParcel'];
$weightPrime=$_POST['primeWeight'];
$nbKm=$_POST['nbKm'];

//FR89370400440532013000

$query=$bdd->prepare("SELECT stripeId,client FROM livreur WHERE id = ?");
$query->execute([$idDeliver]);
$result = $query->fetch();

if($result['stripeId']!=""){
  updateStripeAccount($stripe,$result['stripeId'],$bankAccount);
  $accountId=$result['stripeId'];
  $upDel=$bdd->prepare("UPDATE livreur SET rib=AES_ENCRYPT(?,'pa2021esgi') WHERE id = ?");
  $upDel->execute([$bankAccount,$idDeliver]);
}else{
  $deliver=$bdd->prepare("SELECT * FROM client WHERE id = ?");
  $deliver->execute([$deliverInfo['client']]);
  $deliverInfo=$deliver->fetch();
  $accountId=createStripeAccount($stripe,$deliverInfo['prenom'],$deliverInfo['nom'],$deliverInfo['numPhone'],$deliverInfo['email'],$deliverInfo['adresse'],$deliverInfo['codePostal'],$deliverInfo['ville'],$deliverInfo['birthdate'],$bankAccount);

  $upDel=$bdd->prepare("UPDATE livreur SET stripeId = ?,rib=AES_ENCRYPT(?,'pa2021esgi') WHERE id = ?");
  $upDel->execute([$accountId,$bankAccount,$idDeliver]);
}

$transfer = $stripe->transfers->create([
  'amount' => $amount*100,
  'currency' => 'eur',
  'destination' => $accountId,
]);

if($transfer->id != ""){
  $salary=$bdd->prepare("INSERT INTO salaire(montant,date,nbKm,primeObjectif,primePoids,nbColis,livreur) VALUES (?,NOW(),?,?,?,?,?)");
  $salary->execute([$amount,$nbKm,$primeObjectif,$weightPrime,$nbParcel,$idDeliver]);
}

// $account = $stripe->accounts->retrieve(
//   'acct_1IoBZrPn6e4RpG3L',
//   []
// );

echo "success";
 ?>
