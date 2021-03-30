<?php
session_start();
ini_set('display_errors',1);
require_once( __DIR__ . '/connexionbdd.php');

$bdd = connexionBDD();

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$address = htmlspecialchars(trim($_POST['address']));
$cdPostal = htmlspecialchars(trim($_POST['cdPostal']));
$nbPhone = htmlspecialchars(trim($_POST['nbPhone']));


if($_SESSION['id'] != null){
    if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
        if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])) {
            if (isset($_POST['address']) && strlen($_POST['address']) > 1 && is_string($_POST['address'])) {
                if (isset($_POST['nbPhone']) && strlen($_POST['nbPhone']) == 10 && is_string($_POST['nbPhone'])) {
                    if (isset($_POST['cdPostal']) && strlen($_POST['cdPostal']) == 5 && is_numeric($_POST['cdPostal'])) {
                        $q = 'UPDATE client SET nom = :val1, prenom = :val2, adresse = :val3, codePostal = :val4, numPhone = :val5 WHERE id = :val6';
                        $mod = $bdd->prepare($q);
                        $mod->bindValue(":val1", $lastname, PDO::PARAM_STR);
                        $mod->bindValue(":val2", $firstname, PDO::PARAM_STR);
                        $mod->bindValue(":val3", $address, PDO::PARAM_STR);
                        $mod->bindValue(":val4", $cdPostal, PDO::PARAM_STR);
                        $mod->bindValue(":val5", $nbPhone, PDO::PARAM_STR);
                        $mod->bindValue(":val6", $_SESSION['id'] , PDO::PARAM_INT);
                        $mod->execute();
                        header('Location: ../profile.php');
                    }
                }
            }
        }
    }
}
?>
