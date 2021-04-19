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
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Détails du colis</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="moreInfo">
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-5">
      <h1 class="banner-item text-center mb-3">Veuillez entrer les informations relatives au colis</h1>
      <div class="input-group input-group-lg">
        <input id="search" type="text" class="form-control text-center mt-5" placeholder="Recherche">
      </div>

      <div class="d-flex mt-4">
        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Rechercher par :</p>
          <select id="searchBy" class="form-select" aria-label="Default select example">
            <option selected value="0">N'importe</option>
            <option value="1">Nom</option>
            <option value="2">CodePostal</option>
            <option value="3">Référence</option>
          </select>
        </div>

        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Mode de livraison :</p>
          <select id="speedMode" class="form-select" aria-label="Default select example">
            <option selected value="0">N'importe</option>
            <option value="1">Standard</option>
            <option value="2">Express</option>
          </select>
        </div>

        <div class="flex-fill mx-2">
          <p class="h5 text-center mb-4 banner-item">Status du paiement :</p>
          <select id="paiementStatus" class="form-select" aria-label="Default select example">
            <option selected value="0">N'importe</option>
            <option value="1">Payé</option>
            <option value="2">Paiement en attente</option>
          </select>
        </div>

      </div>

      <div class="d-grid gap-2 mx-auto">
        <button id="submit" type="button" class="btn btn-primary mt-5 btn-lg" onclick="search()"><a class="serviceslink">VALIDER</a></button>
      </div>

      <div id="error"></div>
      <div class="tab-pane mt-5 " role="tabpanel" aria-labelledby="paid-tab">
        <table id="searchResult" class="table border-secondary text-center fs-4">
        </table>
      </div>
    </div>

    <script type="text/javascript">
      function search(){
        let search = document.getElementById('search').value;
        let searchBy = document.getElementById('searchBy').value;
        let speed = document.getElementById('speedMode').value;
        let paiement = document.getElementById('paiementStatus').value;
        //console.log(search+searchBy + speed + paiement);
        $.ajax({
           url : 'searchParcelCompanyProcess.php',
           type : 'POST',
           data : 'search='+search+'&searchBy='+searchBy+'&speed='+speed+'&paiement='+paiement,
           dataType : 'html',
           success : function(result){
             if(result != ''){
               document.getElementById('error').innerHTML = '';
               document.getElementById('searchResult').innerHTML =
               '<thead><tr><th scope="col">Mode de livraison</th> <th scope="col">Destinataire</th><th scope="col">Code Postal</th><th scope="col">Référence</th><th scope="col"></th></tr></thead>' + result;
             }else{
               document.getElementById('searchResult').innerHTML = '';
               document.getElementById('error').innerHTML = '<h1 class="mt-5 error text-center">Aucun colis correspondant aux paramètres de recherche</h1>';
             }
           }
        });
      }

      function showMore(idParcel){
        $.ajax({
           url : 'parcelInfo.php',
           type : 'POST',
           data : 'idParcel='+idParcel,
           dataType : 'html',
           success : function(result){
             document.getElementById('moreInfo').innerHTML = result;
             $(function () {
                $('#exampleModal').modal('toggle');
             });
           }
        });
      }

      function delParcel(idParcel,idClient){
        $.ajax({
           url : 'deleteParcel.php',
           type : 'POST',
           data : 'idParcel='+idParcel+'&idClient='+idClient,
           dataType : 'html',
           success : function(result){
             if(result == 'success'){
               document.getElementById(idParcel).remove();
             }
           }
        });
      }

      $(document).ready(function(){
          $("#search").keypress(function(e){
              if (e.keyCode === 13){
                  search();
                  e.preventDefault();
                  return false;
              }
          });
      });


    </script>
  </body>
</html>
