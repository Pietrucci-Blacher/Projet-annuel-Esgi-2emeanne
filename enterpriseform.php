<?php
require_once(__DIR__ . '/request/enterprise.php');
require_once(__DIR__ . '/request/user.php');
require_once('include/utilities/banuser.php');
checkbanuser();

if(empty($_SESSION) || !$_SESSION['rank'] == "entreprise" || !empty($_SESSION['siret']) || checkfirstconnect() == false){
    header('Location: index.php');
    exit();
}
$siret = htmlspecialchars($_POST['siret']);
$bdd = connexionBDD();
$error = NULL;

$q = "SELECT numSiret FROM entreprise WHERE numSiret = :siret";
$req = $bdd->prepare($q);
$req->bindValue(":siret", $siret,PDO::PARAM_STR);
$req->execute();
$ressiret = $req->fetch(PDO::FETCH_ASSOC);


if($ressiret == 0){
    if(isset($_POST['siret']) && is_numeric($_POST['siret']) && strlen($_POST['siret']) == 14){
        $q = "UPDATE client SET Firstconnect = false WHERE email = :val1";
        $req = $bdd->prepare($q);
        $req->bindValue(':val1',$_SESSION['email'],PDO::PARAM_STR);
        $req->execute();

        EnterprisepushSiret($siret);
        $_SESSION['siret'] = $siret;
        header('Location: index.php');
        exit();
    }
}else{
    $error = "Ce numéro Siret est déjà utilisé";
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
    <script src="js/formenterprise.js"></script>
    <title>Ultimate Parcel - Enregistrement des entreprise</title>
</head>
<body style="background-repeat:no-repeat;"  onCopy="return false" onPaste="return false" onCut="return false">
<?php include('include/header.php'); ?>
<br><br>
<div class="container-fluid">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <h3 class="text-center fw-bold mb-3">Enregistrement Entreprise</h3>
        <form class="border bg-light border-dark rounded text-align mt-5"  id="enterpiseformcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="form-group flex-fill mx-3 mb-2 mt" id="enterpriseform">
                <h4 class=" text-center fw-bold mb-4">Veuillez insérer votre SIRET</h4>
                <input class="form-control my-2 " type="number" name="siret" id="siret" placeholder="SIRET" value="<?php echo $siret ?>">
            <br>
            <div class="form-group mx-5 mb-5 mt-3">
                <input class="form-control" type="submit" name="submit" value="Envoyer">
            </div>
            <h5 class="text-center pb-5"><?php echo $error; ?></h5>
        </form>
    </div>
</div>
<br>
</body>
</html>
