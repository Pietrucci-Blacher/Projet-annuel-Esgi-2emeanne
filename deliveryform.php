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
        <form class="border bg-light border-dark rounded text-align" action="include/deliverycheck.php" id="deliveryformcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="form-group flex-fill mx-3 mb-2" id="driveuploadzone">
                <label for="driveupload" class="fw-bold mb-2" langtrad="UPLO">Veuillez upload votre permis de conduire</label>
                <input class="form-control mb-2" type="file" name="driveupload" id="driveupload">
                <blockquote class="blockquote">
                    <h5 langtrad="EXTA">Extension acceptée : <strong>PDF</strong></h5>
                </blockquote>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-4  mb-2">
                <label for="depot" class="fw-bold mb-2">Veuillez choisir votre dépôt</label>
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
                <label for="radiusdepot" class="fw-bold mb-2">Veuillez sélectionner la distance maximale de livraison autour de votre dépôt</label>
                <select class="form-select" id="radiusdepot" name="radiusdepot">
                    <?php
                    for($i = 30; $i <=300; $i += 10){
                        echo "<option value='$i'>". $i . " km" ."</option>";
                    } ?>
                </select>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-3 mb-2">
                <label for="driveuploadpoints" class="fw-bold mb-2 " langtrad="ULPO2">Veuillez upload un justificat montrant votre nombre de points sur le permis</label>
                <input class="form-control" type="file" name="driveuploadpoints" id="driveuploadpoints">
                <br>
                <blockquote>
                    <dl>
                        <dt> <h5 langtrad="EXTA">Extensions acceptées : </h5></dt>
                        <dd> <h6>- <strong>PDF</strong> </h6></dd>
                        <dd> <h6>- <strong>BMP</strong> </h6></dd>
                        <dd> <h6>- <strong>JPG</strong> </h6></dd>
                        <dd> <h6>- <strong>PNG</strong> </h6></dd>
                    </dl>
                </blockquote>
            </div>
            <hr class="mx-4">
            <div class="form-group flex-fill mx-4 mb-2">
                <input class="form-control" type="text" name="vehiculetype" id="vehiculetype" placeholder="Type de véhicule"><br>
            </div>
            <div class="form-group flex-fill mx-4 mb-2">
                <input class="form-control" type="text" name="brandvehicule" id="brandvehicule" placeholder="Marque de la voiture"><br>
            </div>
            <div class="form-group flex-fill mx-4 mb-2">
                <input class="form-control" type="number" name="ptacvehicule" id="ptacvehicule" placeholder="PTAC (Poids total autorisé en charge) du véhicule " min="0">
            </div>
            <br>
            <div class="form-group mx-5 mb-5">
                <input class="form-control" type="submit" name="submit" value="Envoyer">
            </div>
        </form>
    </div>
</div>
<br>
</body>
</html>
