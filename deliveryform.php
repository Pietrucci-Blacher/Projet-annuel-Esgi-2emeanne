<?php
require_once(__DIR__ . '/request/user.php');
require_once('include/utilities/banuser.php');
checkbanuser();
if(empty($_SESSION) || !$_SESSION['rank'] == "livreur" || checkfirstconnect() == false){
   header('Location: index.php');
   exit();
}
?>

<!Doctype html>
<html lang="fr" dir="ltr">
<head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <meta name="description" content="Page d'enregistrement des livreurs de l'application web Ultimate Parcer">
    <meta name="keywords" content="livraison,colis">
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="js/formdelivery.js"></script>
    <script src="js/translate.js"></script>
    <title>Ultimate Parcel - Enregistrement des livreurs</title>
</head>
<body  onCopy="return false" onPaste="return false" onCut="return false">
<?php include('include/header.php'); ?>
<br><br>
<div class="container-fluid">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <h3 class="text-center fw-bold mb-3" langtrad="ENR">Enregistrement livreurs</h3>
        <br>
        <form class="border bg-light border-dark rounded text-align" action="include/deliverycheck.php" id="deliveryformcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="form-group flex-fill mx-3 mb-2" id="driveuploadzone">
               <div class="text-center">
                <label for="driveupload" class="fw-bold mb-2" langtrad="UPLO">Veuillez upload votre permis de conduire</label>
               </div>   
                <input class="form-control mb-2" type="file" name="driveupload" id="driveupload">
                <blockquote class="blockquote">
                   <dl>
                      <dt><h5 langtrad="EXTA">Extension acceptée : </h5></dt>
                      <dd><h6>- <strong>PNG</strong></h6></dd>
                      <dd><h6>- <strong>JPG</strong></h6></dd>
                      <dd><h6>- <strong>JPEG</strong></h6></dd>
                   </dl>   
                </blockquote>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-4  mb-2">
               <div class="text-center">
                <label langtrad="DEPOSIT" for="depot" class="fw-bold mb-2">Veuillez choisir votre dépôt</label>
               </div>   
                <select class="form-select" id="depot" name="depot">
                    <?php
                    $depots = getdepots();
                    foreach($depots as $depot){ ?>
                        echo "<option value="<?php echo $depot['id'] ?>"><?php echo $depot['adresse'] ?> - <?php echo $depot['ville'] ?> </option>
                    <?php } ?>
                </select>
            </div>
            <br>
            <div class="form-group flex-fill mx-4 mb-2">
               <div class="text-center">
                <label langtrad="RADDEP" for="radiusdepot" class="fw-bold mb-2">Veuillez sélectionner la distance maximale de livraison autour de votre dépôt</label>
               </div> 
                  <select class="form-select" id="radiusdepot" name="radiusdepot">
                    <?php
                    for($i = 30; $i <=300; $i += 10){
                        echo "<option value='$i'>". $i . " km" ."</option>";
                    } ?>
                </select>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-3 mb-2">
               <div class="text-center">
                <label for="driveuploadpoints" class="fw-bold mb-2 " langtrad="UPLO2">Veuillez upload un justificat montrant votre nombre de points sur le permis</label>
               </div> 
                <input class="form-control" type="file" name="driveuploadpoints" id="driveuploadpoints">
                <br>
                <blockquote>
                    <dl>
                        <dt> <h5 langtrad="EXTA">Extensions acceptées : </h5></dt>
                        <dd> <h6>- <strong>PNG</strong> </h6></dd>
                        <dd> <h6>- <strong>JPG</strong> </h6></dd>
                        <dd> <h6>- <strong>JPEG</strong> </h6></dd>
                    </dl>
                </blockquote>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-4 mb-2">
                <input langtrad="TYPE" class="form-control" type="text" name="vehiculetype" id="vehiculetype" placeholder="Type de véhicule"><br>
            </div>
            <div class="form-group flex-fill mx-4 mb-2">
                <input langtrad="BRAND" class="form-control" type="text" name="brandvehicule" id="brandvehicule" placeholder="Marque de la voiture"><br>
            </div>
            <div class="form-group flex-fill mx-4 mb-2">
                <input langtrad="GVWR" class="form-control" type="number" name="ptacvehicule" id="ptacvehicule" placeholder="PTAC (Poids total autorisé en charge) du véhicule " min="0">
            </div>
            <br>
            <h6 langtrad="HELP" class="text-center fw-bold">En cas d'erreurs, veuillez contacter nos administrateurs via la boite mail suivante:</h6>
            <div class="text-center"><a class="fw-bold" href="mailto:ultimate.parcelad@gmail.com">ultimate.parcelad@gmail.com</a></div>
            <br>
            <div class="form-group mx-5 mb-5">
                <input langtrad="SEND" class="form-control" type="submit" name="submit" value="Envoyer">
            </div>
        </form>
    </div>
</div>
<br>
</body>
</html>
