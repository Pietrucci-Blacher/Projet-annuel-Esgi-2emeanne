<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/parcelTracking.css" type="text/css">
    <link rel="stylesheet" href="css/index.css" type="text/css">
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <div class="container mt-5">
      <h1 class="banner-item text-center">Veuillez entrer les informations relatives à votre colis</h1>
      <div class="input-group input-group-lg ">
        <input id="name" type="text" class="form-control text-center mt-5" placeholder="Nom du destinataire">
      </div>
      <div class="input-group input-group-lg mt-4">
        <input id="ref" type="text" class="form-control text-center" placeholder="Référence du colis">
      </div>
      <div class="d-grid gap-2 mx-auto">
        <button id="submit" type="button" class="btn btn-primary mt-5 btn-lg"><a class="serviceslink">VALIDER</a></button>
      </div>
      <br>
      <div id="parcel">

      </div>
    </div>
  </body>

  <script type="text/javascript">
    $("#submit").click(function(){
      var parcel = document.getElementById('parcel');
      var ref = document.getElementById('ref').value;
      var name = document.getElementById('name').value;
      $.ajax({
         url : 'parcelTrackingProcess.php',
         type : 'POST',
         data : 'ref='+ref+'&name='+name,
         dataType : 'html',
         success : function(result){
           parcel.innerHTML = result;
         }
      });
    });

  </script>
</html>
