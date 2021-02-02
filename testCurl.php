<?php
require('include/connexionbdd.php');

$bdd = connexionBDD();

$text = $_POST['text'];

$req = $bdd->prepare("INSERT INTO testcurl (text) VALUES (?)");
$success = $req->execute([$text]);

if($success == true){
    echo 'it works, you sent '.$text.' .';
}else{
    print_r($req->errorInfo());
}
?>
