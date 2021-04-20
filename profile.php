<?php
    require_once __DIR__ . '/request/user.php';
    require_once('include/utilities/banuser.php');
    checkbanuser();
    if(empty($_SESSION)){
        header('Location: connect.php');
        exit(); 
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
    <script src="js/translate.js"></script>
    <title>Ultimate Parcel - Profile page</title>
</head>
<body>
<?php require_once('include/header.php'); ?>
<br>
<div class="container">
    <div class="col-lg-6 mx-auto">
        <h1 class="banner-item text-center" langtrad="PRO">Mon Profil</h1>
        <form class="border bg-light border-dark rounded text-align mt-4" action="include/modifprof.php" method="post" enctype="multipart/form-data">
         <?php
         $users = getData();
         foreach ($users as $user){?>
            <div class="d-flex mt-3 justify-content-center">
             <div class="form-row">
                <div class="form-group flex-fill mx-2">
                    <label for="lastname" langtrad="NAME">Nom : </label>
                    <input id="lastname" class="form-control" name="lastname" type ="text" value="<?php echo $user['nom'] ?>">
                </div>
                <div class="form-group flex-fill mx-2 mt-2">
                    <label for="firstname" langtrad="FIRNAME">Prénom : </label>
                    <input id="firstname" class="form-control" name="firstname" type ="text" value="<?php echo $user['prenom'] ?>">
                </div>
                <?php if($_SESSION['rank'] == "livreur"){ ?>
                 <div class="d-flex justify-content-end mt-2">
                     <div class="form-row">
                             <label for="permis" langtrad="PER">Votre permis : </label>
                             <input type="hidden" name="MAX_FILE_SIZE" value="4194304"/>
                             <input id="permis" class="form-control" name="uploadpermis" type="file" value="test"/>
                     </div>
                 </div>
               <?php } ?>
             </div>
            </div>
             <hr class="mx-4">
             <div class="d-flex mt-3 justify-content-center">
                <div class="form-row">
                    <div class=" mx-3">
                       <label for="address" langtrad="ADD">Adresse : </label>
                      <input id="address" class="form-control" name="address" type ="text" value="<?php echo $user['adresse'] ?>">
                    </div>
                    <div class="form-group flex-fill mx-3 mt-2">
                        <label for="cdPostal" langtrad="CDEPO">Code Postal : </label>
                        <input id="cdPostal" class="form-control" name="cdPostal" type ="text" value="<?php echo $user['codePostal'] ?>">
                    </div>
                </div>
             </div>
             <hr class="mx-4">
            <div class="d-flex mt-3 justify-content-center">
                 <div class="form-row">
                        <div class="form-group flex-fill mx-2">
                        <label for="nbPhone" langtrad="NBP">Numéro de téléphone : </label>
                        <input id="nbPhone" class="form-control" name="nbPhone" type ="text" value="<?php echo $user['numPhone'] ?>">
                    </div>
                    <div class="form-group flex-fill mx-2 mt-2">
                        <label for="email">Email : </label>
                        <input id="email" class="form-control" name="email" type ="text" value="<?php echo $user['email'] ?>" disabled>
                    </div>
                    <?php if(!empty($_SESSION['siret']) && ($_SESSION['rank'] == "entreprise")){ ?>

                    <div class="form-group flex-fill mx-2 mt-2">
                        <label for="siret" langtrad="SIR">Numéro siret : </label>
                        <input id="siret" class="form-control" name="siret" type ="text" value="<?php echo $_SESSION['siret'] ?>" disabled>
                    </div>
                  <?php } ?>
                 </div>
            </div>
            <div id="divpush" class="d-flex mt-4 justify-content-center">
                <div id="divpus" class="form-group mx-5 mb-5">
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
