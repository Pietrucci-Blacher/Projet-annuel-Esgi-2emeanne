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
    <h1 class="banner-item text-center">Mon Profil</h1>
    <form class="border bg-light border-dark rounded text-align" action="include/registercheck.php" method="post" enctype="multipart/form-data">
        <?php
        $users = getData();
        foreach ($users as $user){?>
            <div class="form-row">
                <br>
                <div class="form-group col-md-6">
                    <label for="name">Nom: </label>
                    <input id="lastname" class="form-control" name="lastname" type ="text" value="<?php echo $user['lastname'] ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Prenom: </label>
                    <input id="firstname" class="form-control" name="firstname" type ="text" value="<?php echo $user['firstname'] ?>">
                </div>
            </div>
    <?php } ?>
    </form>
</body>
</html>