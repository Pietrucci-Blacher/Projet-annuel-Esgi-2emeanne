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
$nbKm=$_POST['$nbKm'];

//FR89370400440532013000

$query=$bdd->prepare("SELECT stripeId FROM livreur WHERE id = ?");
$query->execute([$idDeliver]);
$result = $query->fetch();

if($result['stripeId']!=""){
  updateStripeAccount($stripe,$result['stripeId'],$accountId);
  $accountId=$result['stripeId'];

}else{
  $deliver=$bdd->prepare("SELECT * FROM client WHERE id = ?");
  $deliver->execute([$idDeliver]);
  $deliverInfo=$deliver->fetch();

  $accountId=createStripeAccount($stripe,$deliverInfo['prenom'],$deliverInfo['nom'],$deliverInfo['numPhone'],$deliverInfo['email'],$deliverInfo['adresse'],$deliverInfo['codePostal'],$deliverInfo['ville'],$deliverInfo['birthdate'],$bankAccount);

  $upDel=$bdd->prepare("UPDATE livreur SET stripeId = ? WHERE id = ?");
  $upDel->execute([$accountId,$idDeliver]);
}

$transfer = $stripe->transfers->create([
  'amount' => $amount*100,
  'currency' => 'eur',
  'destination' => $accountId,
]);

if($transfer != ""){
  $salary=$bdd->prepare("INSERT INTO salaire(montant,date,nbKm,primeObjectif,primePoids,nbColis,livreur) VALUES (?,NOW(),?,?,?,?,?)");
  $salary->execute([$amount,$nbKm,$primeObjectif,$weightPrime,$nbParcel,$idDeliver]);
}

// $account = $stripe->accounts->retrieve(
//   'acct_1IoBZrPn6e4RpG3L',
//   []
// );

// echo json_encode($account);
 ?>
