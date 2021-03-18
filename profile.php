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
            <div class="d-flex mt-3 justify-content-start">
             <div class="form-row">
                <div class="form-group flex-fill mx-2">
                    <label for="lastname">Nom: </label>
                    <input id="lastname" class="form-control" name="lastname" type ="text" value="<?php echo $user['nom'] ?>">
                </div>
                <div class="form-group flex-fill mx-2">
                    <label for="firstname">Prenom: </label>
                    <input id="firstname" class="form-control" name="firstname" type ="text" value="<?php echo $user['prenom'] ?>">
                </div>
                 <div class="d-flex flex-fill justify-content-end">
                     <div class="form-row">
                             <label for="permis">Votre permis: </label>
                             <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                             <input id="permis" class="form-control" name="uploadpermis" type="file" value="test">
                     </div>
                 </div>
             </div>
            </div>
            <br>
             <hr class="mx-4">
             <div class="d-flex mt-3 justify-content-start">
                <div class="form-row">
                    <div class="form-group flex-fill mx-3">
                       <label for="addresse">Adresse: </label>
                      <input id="addresse" class="form-control" type ="text" value="<?php echo $user['adresse'] ?>">
                    </div>
                    <div class="form-group flex-fill mx-3">
                        <label for="cdPostal">Code Postal: </label>
                        <input id="cdPostal" class="form-control" name="cdPostal" type ="text" value="<?php echo $user['codePostal'] ?>">
                    </div>
                </div>
             </div>
             <hr class="mx-4">
            <div class="d-flex mt-3 justify-content-start">
                 <div class="form-row">
                        <div class="form-group flex-fill mx-2">
                        <label for="nbPhone">Numéro: </label>
                        <input id="nbPhone" class="form-control" name="nbPhone" type ="text" value="<?php echo $user['numPhone'] ?>">
                    </div>
                    <div class="form-group flex-fill mx-2">
                        <label for="mail">Email: </label>
                        <input id="mail" class="form-control" name="nbPhone" type ="text" value="<?php echo $user['email'] ?>" disabled>
                    </div>
                 </div>
            </div>
            <div class="d-flex mt-3 justify-content-middle">
                <div class="form-group mx-5 mb-5">
                    <input class="form-control" type="submit" value="Modifier mes informations">
                </div>

            </div>
             <br>
    <?php } ?>
        </form>
    </div>
</div>
</body>
</html>