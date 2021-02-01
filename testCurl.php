<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=testcurl;charset=utf8','root','root');

$text = $_POST['text'];

$req = $pdo->prepare("INSERT INTO test (text) VALUES (?)");
$success = $req->execute([$text]);

if($success == true){
    echo 'it works';
}else{
    print_r($req->errorInfo());
}
?>
