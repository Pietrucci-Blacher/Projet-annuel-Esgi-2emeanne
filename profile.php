<?php
    require_once('request/user.php');
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
    <title>Ultimate Parcel - Profile page</title>
</head>
<body>
    <?php require_once('include/header.php'); ?>

    <div class="h1 textcenter"><?php echo $_SESSION['name']; ?></div>
        <div class="mt-5 banner">
            <div class="container">
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