<?php

require_once( __DIR__ . '/connexionbdd.php');

$bdd = connexionBDD();

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$email = htmlspecialchars(trim($_POST['email']));
$address = htmlspecialchars(trim($_POST['address']));
$cdPostal = htmlspecialchars(trim($_POST['cdPostal']));
$nbPhone = htmlspecialchars(trim($_POST['nbPhone']));


$q = "SELECT id FROM client WHERE email = :val1";
$req = $bdd->prepare($q);
$req->bindValue(':val1',$_POST['email'],PDO::PARAM_STR);
$req->execute();
$checkempty= $req->fetch();

if($checkempty == 0){
    if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
        if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])){
            if(isset($_POST['address']) && strlen($_POST['address']) > 1 && is_string($_POST['address'])){
                if(isset($_POST['nbPhone']) && strlen($_POST['nbPhone']) == 10 && is_string($_POST['nbPhone'])){
                    if(isset($_POST['cdPostal']) && strlen($_POST['cdPostal']) == 5 && is_numeric($_POST['cdPostal'])){
                        $q = 'INSERT INTO client(nom, prenom, email, adresse, codePostal, numPhone) VALUES (:val1,:val2,:val3,:val4,:val5,:val6)';
                        $req = $bdd->prepare($q);
                        $req->bindValue(":val1", $lastname, PDO::PARAM_STR);
                        $req->bindValue(":val2", $firstname, PDO::PARAM_STR);
                        $req->bindValue(":val3", $email, PDO::PARAM_STR);
                        $req->bindValue(":val4", $address, PDO::PARAM_STR);
                        $req->bindValue(":val5", $cdPostal, PDO::PARAM_STR);
                        $req->bindValue(":val6", $nbPhone, PDO::PARAM_STR);
                        $req->execute();
                        header('Location: ../profile.php');
                        exit();
                    }

            }
    }
}