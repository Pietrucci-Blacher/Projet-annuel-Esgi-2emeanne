<?php
require_once('include/connexionbdd.php');

$bdd = connexionBDD();

$text = $_POST['text'];

$req = $bdd->prepare("INSERT INTO testcurl (text) VALUES (:val1)");
$req->bindValue(':val1',$text,PDO::PARAM_STR); 
$success = $req->execute();

if($success == true){
    echo 'it works, you sent '.$text.' .';
}else{
    print_r($req->errorInfo());
}
