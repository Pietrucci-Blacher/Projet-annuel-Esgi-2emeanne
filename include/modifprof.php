<?php

require_once( __DIR__ . '/connexionbdd.php');

$bdd = connexionBDD();

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$email = htmlspecialchars(trim($_POST['email']));
$address = htmlspecialchars(trim($_POST['address']));
$cdPostal = htmlspecialchars(trim($_POST['cdPostal']));
$nbPhone = htmlspecialchars(trim($_POST['nbPhone']));


/*$q = "SELECT nom, prenom, email, adresse, codePostal, numPhone FROM client WHERE id = :id";
$req = $bdd->prepare($q);
$req->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
$req->execute();
$checkempty= $req->fetchAll(PDO::FETCH_ASSOC);*/

//if($checkempty != 0){
    if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
        if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])) {
            if (isset($_POST['address']) && strlen($_POST['address']) > 1 && is_string($_POST['address'])) {
                if (isset($_POST['nbPhone']) && strlen($_POST['nbPhone']) == 10 && is_string($_POST['nbPhone'])) {
                    if (isset($_POST['cdPostal']) && strlen($_POST['cdPostal']) == 5 && is_numeric($_POST['cdPostal'])) {
                        $q = 'UPDATE client SET nom = :val1, prenom = :val2, adresse = :val4, codePostal = :val5, numPhone = :val6 WHERE email = :val3)';
                        $mod = $bdd->prepare($q);
                        $mod->bindValue(":val1", $lastname);
                        $mod->bindValue(":val2", $firstname);
                        $mod->bindValue(":val3", $email);
                        $mod->bindValue(":val4", $address);
                        $mod->bindValue(":val5", $cdPostal);
                        $mod->bindValue(":val6", $nbPhone);
                        $mod->execute();
                        header('Location: ../index.php');
                        exit();
                    }
                }
            }
        }
    }
//}