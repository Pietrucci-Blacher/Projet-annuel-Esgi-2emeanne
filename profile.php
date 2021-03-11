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

    <div class="h1 textcenter">Mon Profil</div>
    <br>
        <div class="container mt-5">
                <div class="row">
    <?php

    $users = getData();

    foreach ($users as $user){?>
                <ul>
                    <li>   <?php echo $user['nom']; ?>  </li>
                    <br>
                    <li>  <?php echo $user['prenom'] ?></li>
                    <br>
                    <li>  <?php echo $user['adresse'] ?></li>
                    <br>
                    <li>  <?php echo $user['ville'] ?></li>
                    <br>
                    <li>  <?php echo $user['codePostal'] ?></li>
                    <br>
                    <li>  <?php echo $user['numPhone'] ?></li>
                </ul>

    <?php } ?>

                </div>
             </div>
</body>
</html>