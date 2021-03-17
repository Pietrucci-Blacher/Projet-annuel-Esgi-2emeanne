<?php
ini_set('display_errors',1);
require_once( __DIR__ . '/connexionbdd.php');
require_once(__DIR__ . '/variable.php');
require_once('../request/user.php');
if(empty($_POST) || empty($_FILES) || checkfirstconnect() == false){
    header('Location: ../index.php');
}

$bdd = connexionBDD();
$filepermis = $_FILES['driveupload']['name'];
$validextensionpermis = array('pdf');
$path = "files/document/permis/";
$filepath = strtolower(substr($filepermis,-3));
$checkpoints = $_FILES['driveuploadpoints']['name'];
$validextensionpoints = array('pdf', 'jpg', 'bmp', 'png');
$filepathpoints = strtolower(substr($checkpoints,-3));
$geozone = $_POST['geozone'];
$vehiculetype = htmlspecialchars(trim($_POST['vehiculetype']));
$vehiculebrand = htmlspecialchars(trim($_POST['brandvehicule']));
$vehiculeptac = htmlspecialchars(trim($_POST['ptacvehicule']));

$filenameperm = "per";
$filenameperm .= date("dmY");
$filenameperm .= "_";
$filenameperm .= $_SESSION['id'];

$filenamepoints = "pts";
$filenamepoints .= date("dmY");
$filenamepoints .= "_";
$filenamepoints .= $_SESSION['id'];

if(!empty($_FILES) && isset($_POST['submit'])) {
    if (in_array($filepath, $validextensionpermis) && $_FILES['driveupload']['size'] <= 3 * GB && !is_uploaded_file($filepermis)) {
        $throwback = move_uploaded_file($_FILES['driveupload']['tmp_name'], SITE_ROOT."/" .  $path . $filenameperm . "." . $filepath);
    }

    if (in_array($filepathpoints, $validextensionpoints) && $_FILES['driveuploadpoints']['size'] <= 3 * GB && !is_uploaded_file($checkpoints)){
        $throwback = move_uploaded_file($_FILES['driveuploadpoints']['tmp_name'],SITE_ROOT . "/" .  $path . $filenamepoints . "." . $filepathpoints);
    }

    if(isset($_POST['geozone'])){
        if(isset($_POST['vehiculetype']) && preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]+$/', $_POST['vehiculetype'])){
            if(isset($_POST['brandvehicule']) && preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]+$/', trim($_POST['brandvehicule']))){
                if(isset($_POST['ptacvehicule']) && is_numeric($_POST['ptacvehicule'])){
                    $q = "UPDATE client SET Firstconnect = false WHERE email = :val1";
                    $req = $bdd->prepare($q);
                    $req->bindValue(':val1',$_SESSION['email'],PDO::PARAM_STR);
                    $req->execute();

                    $qu = "INSERT INTO livreur(zoneGeo, etatPermis, lienPermis, vehiculetype, brandvehicule, ptacvehicule, client) VALUES(:val1,:val2,:val3,:val4,:val5,:val6,:val7)";
                    $req = $bdd->prepare($qu);
                    $req->bindValue(':val1',$geozone, PDO::PARAM_STR);
                    $req->bindValue(':val2',$path.$filenamepoints, PDO::PARAM_STR);
                    $req->bindValue(':val3',$path.$filenameperm, PDO::PARAM_STR);
                    $req->bindValue(':val4', $vehiculetype, PDO::PARAM_STR);
                    $req->bindValue(':val5', $vehiculebrand, PDO::PARAM_STR);
                    $req->bindValue(':val6',$vehiculeptac, PDO::PARAM_STR);
                    $req->bindValue(':val7', $_SESSION['id'], PDO::PARAM_INT);
                    $req->execute();
                    header('Location: ../index.php');
                    exit();
                }
            }
        }
    }
}