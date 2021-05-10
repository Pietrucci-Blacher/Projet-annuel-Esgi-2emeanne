<?php
require_once('include/utilities/banuser.php');
checkbanuser();
if(empty($_SESSION['siret'])){
    header('Location: enterpriseform.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <script src="https://js.stripe.com/v3/"></script>
      <script src="js/translate.js"></script>

  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <?php
      require_once('include/connexionbdd.php');

      $bdd = connexionBDD();

      $totalPrice = 0;
      $totalParcel = 0;
      $queryParcel = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non'");
      $queryParcel->execute([$_SESSION['siret']]);

      while($parcel = $queryParcel->fetch()){
        $totalPrice += $parcel['prix'];
        $totalParcel +=1;
      }
     ?>

     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel"></h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body" id="moreInfo">
             <div class="container px-5">
               <div class="input-group mt-2">
                 <input id="name" type="text" class="form-control text-center border fw-bold" placeholder="NOM" autocomplete="off">
               </div>
               <div class="input-group mt-4">
                 <input id="zipcode" type="text" class="form-control text-center border fw-bold" placeholder="CODE POSTAL" autocomplete="off">
               </div>
               <div class="mt-4">
                 <div id="card-element"  class="stripeField">
                 </div>
               </div>
               <div class="d-grid gap-2 mx-auto">
                 <button id="submit" type="button" class="btn btn-primary mt-4" onclick="generateToken()"><a class="serviceslink" id="btnTxt"></a></button>
               </div>
               <div id="card-errors"  class="text-center mt-3 error"></div>
             </div>
             <p class="mt-3 mb-1 me-2 fw-bold float-end" langtrad="PAISTR">🔒 Paiement sécurisé par STRIPE</p>
           </div>
         </div>
       </div>
     </div>

    <div class="container mt-5">
      <h1 class="banner-item text-center" langtrad="PAIC">Paiement de vos colis</h1>
      <div class="d-flex justify-content-center mt-5">
        <h2 class="banner-item w-50 text-center" langtrad="TOTPA">Total à payer : <?php echo $totalPrice ?> €</h2>
        <h2 class="banner-item w-50 text-center" langtrad="NBCO">Nombre de colis en attente : <?php echo $totalParcel ?> </h2>
      </div>
      <?php if($totalParcel != 0){ ?>
      <div class="d-grid gap-2 mx-auto">
        <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="displayModal(<?php echo $totalParcel ?>,<?php echo $totalPrice ?>)"><a class="serviceslink h4" langtrad="PAC">Payer les colis</a></button>
      </div>
      <h1 class="banner-item text-center mt-5" langtrad="DETPA">Détails du paiement</h1>
      <table class=" mt-5 table banner table-bordered text-center banner-item fs-4">
        <thead>
          <tr>
            <th scope="col" langtrad="1">Référence</th>
            <th scope="col" langtrad="2">Mode de livraison</th>
            <th scope="col" langtrad="3">Poids</th>
            <th scope="col" langtrad="4">Prix</th>
          </tr>
        </thead>
        <tbody>
            <?php
            $queryParcelBis = $bdd->prepare("SELECT * FROM colis WHERE entreprise = ? AND statusPaiement = 'non' ORDER BY poids");
            $queryParcelBis->execute([$_SESSION['siret']]);

            while($parcelBis = $queryParcelBis->fetch()){
              echo'
                  <tr>
                    <td>'.$parcelBis['refQrcode'].'</td>
                    <td>'.$parcelBis['modeLivraison'].'</td>
                    <td>'.$parcelBis['poids'].'</td>
                    <td>'.$parcelBis['prix'].' €</td>
                  </tr>
                  ';
            }

            echo '<tr>
                <td colspan="2"></td>
                <th scope="col" langtrad="TOTPA">Total à payer</th>
                <td class="fw-bold">'.$totalPrice.' €</td>
                </tr>';
             ?>
        </tbody>
      </table>
    <?php } ?>
    </div>
    <script type="text/javascript">

      function displayModal(nbParcel,totalPrice){
        document.getElementById('exampleModalLabel').innerHTML = "Paiement de vos "+nbParcel+" colis en attente";
        document.getElementById('btnTxt').innerHTML = "Payer "+totalPrice+" €";
        $('#exampleModal').modal('toggle');
      }

    </script>
    <script type="text/javascript">
      var stripe = Stripe('pk_test_51IOoGkAympjcdUislCnFLyUKEcpV1zt08ZfwxAnw8uaxnrkLON5jBXbnEdAtK54sc3Jg2jK28FaXxqXsFRKc4zjA00833D4MXa');
      var elements = stripe.elements();
      var style = {
        base: {
          fontSize: '18px',
          fontWeight: 600,
        },
      };

      var card = elements.create('card', {hidePostalCode: true,style: style});

      card.mount('#card-element');

      function generateToken(){
        let nameInput = document.getElementById('name');
        let zipInput = document.getElementById('zipcode');
        let error = document.getElementById('card-errors');
        if(nameInput.value == "" || zipInput.value == ""){
          error.innerHTML = "Merci de remplir les champs NOM et CODE POSTAL";
        }else{
          let extraDetails = {
            name: nameInput.value,
            address_zip: zipInput.value
          }
          stripe.createToken(card, extraDetails).then(function(result) {
            if (result.error) {
              error.innerHTML = result.error.message;
            } else {
              $.ajax({
                 url : 'verifyPaiement.php',
                 type : 'POST',
                 data : 'stripeToken='+result.token.id,
                 dataType : 'html',
                 success : function(result){
                   if(result == "succeeded"){
                     $.ajax({
                        url : 'generateBill.php',
                        success : function(result){
                          window.location.href = 'billHistoric.php';
                        }
                     });
                   }else{
                     error.innerHTML = "Erreur lors du paiement, vérifier vos informations et/ou réessayer plus tard";
                   }
                 }
              });
            }
          });
        }
      }
    </script>
  </body>

</html>
