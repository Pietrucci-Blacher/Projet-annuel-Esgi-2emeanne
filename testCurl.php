<?php
$pdo = new PDO('mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_a4b01b2a0b88f60','bdd1b420797f42','190eb870');

$text = $_POST['text'];

$req = $pdo->prepare("INSERT INTO testcurl (text) VALUES (?)");
$success = $req->execute([$text]);

if($success == true){
    echo 'it works';
}else{
    print_r($req->errorInfo());
}
?>
