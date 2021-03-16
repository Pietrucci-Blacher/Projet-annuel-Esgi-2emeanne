<?php
    require_once __DIR__ . '/request/user.php';
    if(empty($_SESSION)){
        header('Location: connect.php');
    }
?>

<!Doctype html>
<html lang="fr" dir="ltr">
<head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <meta name="description" content="Page de profil utilisateur">
    <meta name="keywords" content="livraison,colis">
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <title>Ultimate Parcel - Profile page</title>
</head>
<body>
<?php require_once('include/header.php'); ?>
<br>
<div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <h1 class="banner-item text-center">Mon Profil</h1>
        <form class="border bg-light border-dark rounded text-align" action="include/registercheck.php" method="post" enctype="multipart/form-data">
         <?php
         $users = getData();
         foreach ($users as $user){?>
            <div class="form-row">
                <br>
                <div class="form-group col-md-6">
                    <label for="lastname">Nom: </label>
                    <input id="lastname" class="form-control" name="lastname" type ="text" value="<?php echo $users['nom'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="firstname">Prenom: </label>
                    <input id="firstname" class="form-control" name="firstname" type ="text" value="<?php echo $users['prenom'] ?>">
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="form-group col-md-6">
                   <label for="addresse">Adresse: </label>
                  <input id="addresse" class="form-control" type ="text" value="<?php echo $users['adresse'] ?>">
                  <label for="cdPostal">Code Postal: </label>
                    <input id="cdPostal" class="form-control" name="cdPostal" type ="text" value="<?php echo $users['codePostal'] ?>">
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="nbPhone">Numéro: </label>
                    <input id="nbPhone" class="form-control" name="nbPhone" type ="text" value="<?php echo $users['numPhone'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="mail">Email: </label>
                    <input id="mail" class="form-control" name="nbPhone" type ="text" value="<?php echo $users['email'] ?>">
                </div>
            </div>
    <?php } ?>
        </form>
    </div>
</div>
</body>
</html>