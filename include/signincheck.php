<?php
require_once('../request/user.php');
require_once('../request/enterprise.php');
require_once(__DIR__.'/connexionbdd.php');

$bdd = connexionBDD();

$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? sha1(htmlspecialchars($_POST['password'])) : '';
$remember = $_POST['remember'];

$req = $bdd->prepare('SELECT id,nom,email,mdp,status FROM client WHERE email = :mail AND mdp = :mdp');
$req->bindValue(":mail",$email,PDO::PARAM_STR);
$req->bindValue(":mdp",$password,PDO::PARAM_STR);
$req->execute();
if($data = $req->fetch()){
	$_SESSION['rank'] = $data['status'];
	$_SESSION['email'] = $data['email'];
	$_SESSION['id'] = $data['id'];
	$_SESSION['name'] = $data['nom'];
	setcookie("rank",$data['status'],time() + 1814400);
	if(!empty($_POST["remember"])) {
		setcookie ("email",$_POST['email'],time() + 31*24*3600, null, null, true);
		setcookie("nom", $data['nom'], time() + 31*24*3600,null, null, true);
		setcookie ("password",$_POST['password'],time() + 31*24*3600,null,null,true);
	} else {
		setcookie("email","");
		setcookie("password","");
		setcookie("rank", "");
	}
	if($data['status'] == "entreprise" && checkfirstconnect() == false){
        $siret = getSiretByid($_SESSION['id']);
        $_SESSION['siret'] = $siret;
        header('location: ../index.php');
        exit();
    }else if($data['status'] == "livreur" && checkfirstconnect() == true){
	    header('Location: ../deliveryform.php');
        exit();
    }else if($data['status'] == "entreprise" && checkfirstconnect() == true){
        header('location: ../enterpriseform.php');
        exit();
    }else{
        header('location: ../index.php');
        exit();
    }
}else{
	header('location: ../connect.php');
    exit();
}
