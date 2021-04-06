<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$idPrice=$_POST['idPrice'];

$query=$bdd->prepare("SELECT prixStandard,prixExpress,poidsMax FROM tarifcolis WHERE id = ?");
$query->execute([$idPrice]);

$priceInfo=$query->fetch();

if($priceInfo['poidsMax'] >30){
  echo '<div class="text-center">
        <h4 class="my-3">Prix standard <br> (par tranche de 20kg)</h4>
        <div class="d-flex justify-content-center">
          <input id="inputS" type="number" value="'.$priceInfo['prixStandard'].'" class="text-center ms-4 fs-4">
          <h4 class="ms-3">€</h4>
        </div>';
}
else if($priceInfo['poidsMax'] <= 30){
  echo '<div class="text-center">
        <h4 class="my-3">Prix standard</h4>
        <div class="d-flex justify-content-center">
          <input id="inputS" type="number" value="'.$priceInfo['prixStandard'].'" class="text-center ms-4 fs-4">
          <h4 class="ms-3">€</h4>
        </div>
        <h4 class="my-3">Prix express</h4>
        <div class="d-flex justify-content-center">
          <input id="inputE" type="number" value="'.$priceInfo['prixExpress'].'" class="text-center ms-4 fs-4">
          <h4 class="ms-3">€</h4>
        </div>';
}

echo '<br>
      <div class="d-grid gap-2 col-6 mx-auto">
        <button class="btn btn-primary my-3" onclick="modifyParcel('.$priceInfo['poidsMax'].')">Modifier</button>
      </div>
    </div>';

 ?>
