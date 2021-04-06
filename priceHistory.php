<?php
session_start();
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$weight = $_POST['weight'];

$query = $bdd->prepare("SELECT prixStandard,prixExpress,date FROM tarifcolis WHERE poidsMax = ? ORDER BY date DESC");
$query->execute([$weight]);

echo '<table class="table text-center ">
      <thead>
      <tr>';

if($weight <= 30){
  echo '<th scope="col" class="fs-5">Prix Express</th>
        <th scope="col" class="fs-5">Prix Standard</th>';
}elseif ($weight>30){
  echo '<th scope="col" class="fs-5">Prix Standard <br>(par tranche de 20 kg)</th>';
}

echo '<th scope="col" class="fs-5">Date</th>
      </tr>
      </thead>
      <tbody>';

while($price = $query->fetch()){
  if($weight <= 30){
    echo '<tr>
            <td class=" fs-5">'.$price['prixStandard'].' €</td>
            <td class=" fs-5">'.$price['prixExpress'].' €</td>
            <td class=" fs-5">'.date('d/m/Y', strtotime($price['date'])).'</td>
          </tr>';
    }else{
      echo '<tr>
              <td class=" fs-5">'.$price['prixStandard'].' €</td>
              <td class="fs-5">'.date('d/m/Y', strtotime($price['date'])).'</td>
            </tr>';
    }
}

echo '</tbody>
</table>';
 ?>
