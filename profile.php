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
        <div class="container mt-5">
            <h1 class="banner-item text-center">Mon Profil</h1>
            <div class="row">
    <?php

    $users = getData();

    foreach ($users as $user){?>
                <ul list-style="none">
                    <li><p class="banner-item text-center h5">Nom: <?php echo $user['nom']; ?> </p> </li>
                    <br>
                    <li><p class="banner-item text-center h5">Prénom: <?php echo $user['prenom'] ?> </p></li>
                    <br>
                    <li><p class="banner-item text-center h5">Adresse: <?php echo $user['adresse'] . ' ' . $user['ville'] ?></p></li>
                    <br>
                    <li><p class="banner-item text-center h5">Code Postal: <?php echo $user['codePostal'] ?></p></li>
                    <br>
                    <li><p class="banner-item text-center h5">Numero: <?php echo $user['numPhone'] ?></p></li>
                </ul>

    <?php } ?>

                </div>

            <p class="banner-item text-center h5">Vous souhaitez mettre à jour vos informations ? N'attendez plus et <?php echo "<a href=\"modifprof.php\">ici<\a>"; ?></p>
             </div>
</body>
</html>