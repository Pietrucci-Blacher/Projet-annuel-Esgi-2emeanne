<?php
require_once( __DIR__ . '/connexionbdd.php');
//require_once('../requests/curlrequests.php'); 

$bdd = connexionBDD();

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$email = htmlspecialchars(trim($_POST['email']));
$password = sha1(htmlspecialchars(trim($_POST['password'])));
$address = htmlspecialchars(trim($_POST['address'])); 
$zipcode = htmlspecialchars(trim($_POST['zipcode']));
$city = htmlspecialchars(trim($_POST['city'])); 
$compinfo = htmlspecialchars(trim($_POST['compinfo']));
$phonenum = htmlspecialchars(trim($_POST['phonenum']));

$q = "SELECT id FROM client WHERE email = :val1";
$req = $bdd->prepare($q);
$req->bindValue(':val1',$_POST['email'],PDO::PARAM_STR);
$req->execute();
$resultcheck = $req->fetch(); 

// Faire la vérification de  l'adresse et de l'adresse postale  via Curl$handle = curl_init();
//htmlspecialchars_decode pour la comparaison avec le mdp

if($resultcheck == 0){
    if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
        if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])){
            if(isset($_POST['email']) && $_POST['email'] == $_POST['emailcheck'] && filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                if(isset($_POST['password']) && isset($_POST['passwordcheck']) && $_POST['password'] == $_POST['passwordcheck'] && preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/",$_POST['password'])){
                    if(isset($_POST['address']) && isset($_POST['city']) && strlen($_POST['address']) > 1 && strlen($_POST['city']) > 1 && is_string($_POST['address']) && is_string($_POST['city'])){
                        if(isset($_POST['zipcode']) && strlen($_POST['zipcode']) == 5 && is_numeric($_POST['zipcode'])){
                            if(isset($_POST['compinfo'])  && is_string($_POST['compinfo'])){
                                if(isset($_POST['phonenum']) && strlen($_POST['phonenum']) == 10 && is_string($_POST['phonenum'])){
                                    $q = 'INSERT INTO client(nom, prenom, email, mdp, adresse, ville, codePostal, info, numPhone) VALUES (:val1,:val2,:val3,:val4,:val5,:val6,:val7,:val8,:val9)';
                                    $req = $bdd->prepare($q);
                                    $req->bindValue(":val1",$lastname,PDO::PARAM_STR);
                                    $req->bindValue(":val2",$firstname,PDO::PARAM_STR); 
                                    $req->bindValue(":val3",$email,PDO::PARAM_STR);
                                    $req->bindValue(":val4",$password,PDO::PARAM_STR);
                                    $req->bindValue(":val5",$address,PDO::PARAM_STR);
                                    $req->bindValue(":val6",$city,PDO::PARAM_STR);
                                    $req->bindValue(":val7",$zipcode,PDO::PARAM_STR);
                                    $req->bindValue(":val8",$compinfo,PDO::PARAM_STR);
                                    $req->bindValue(":val9",$phonenum,PDO::PARAM_STR);
                                    $req->execute();
                                    header('Location: ../index.php');
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}




