<?php
require_once( __DIR__ . '/connexionbdd.php');
require_once(__DIR__ . '/variable.php');
require_once('../request/user.php');
require('../cloudinary/Cloudinary.php');
require('../cloudinary/Uploader.php');
require('../cloudinary/Api.php');
require('../cloudinary/Error.php');
\Cloudinary::config(array(
    "cloud_name" => "hvrzhzxky",
    "api_key" => "812238978328538",
    "api_secret" => "_TOLF-WD9wC2a1kBHDDDGGTAHAg"
));
if(empty($_POST) || empty($_FILES) || checkfirstconnect() == false){
    header('Location: ../index.php');
}

$bdd = connexionBDD();
$filepermis = $_FILES['driveupload']['name'];
$path = "uploadperm/";
$filepath = strtolower(substr($filepermis,-3));
$checkpoints = $_FILES['driveuploadpoints']['name'];
$validextensions = array('jpg', 'png', 'jpeg');
$filepathpoints = strtolower(substr($checkpoints,-3));
$depot = $_POST['depot'];
$radiusdepot = $_POST['radiusdepot'];
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
    if (in_array($filepath, $validextensions) && $_FILES['driveupload']['size'] <= 3 * GB) {
        if(move_uploaded_file($_FILES['driveupload']['tmp_name'], SITE_ROOT."/" .  $path . $filenameperm . "." . $filepath)){
            $file = "../" . $path.$filenameperm . "." . $filepath; 
            $result = \Cloudinary\Uploader::upload($file,["resource_type" => "auto", "folder" => "uploadPermis/uploadPerm"]);
            $urlpermis = $result['url'];
        }
    }

    if (in_array($filepathpoints, $validextensions) && $_FILES['driveuploadpoints']['size'] <= 3 * GB){
        if(move_uploaded_file($_FILES['driveuploadpoints']['tmp_name'], SITE_ROOT."/" .  $path . $filenamepoints . "." . $filepath)){
            $file = "../" . $path.$filenamepoints . "." . $filepath;
            $result = \Cloudinary\Uploader::upload($file,["resource_type" => "auto", "folder" => "uploadPermis/uploadPoints"]);
            $urlpoints = $result['url'];
        }
    }

    if(isset($_POST['depot'])){
        if(isset($_POST['radiusdepot'])){
            if(isset($_POST['vehiculetype']) && preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]+$/', $_POST['vehiculetype'])){
                if(isset($_POST['brandvehicule']) && preg_match('/^([a-zA-Z]+\s)*[a-zA-Z]+$/', trim($_POST['brandvehicule']))){
                    if(isset($_POST['ptacvehicule']) && is_numeric($_POST['ptacvehicule'])){
                        $q = "UPDATE client SET Firstconnect = false WHERE email = :val1";
                        $req = $bdd->prepare($q);
                        $req->bindValue(':val1',$_SESSION['email'],PDO::PARAM_STR);
                        $req->execute();

                        $qu = "INSERT INTO livreur(zoneGeo, etatPermis, lienPermis, vehiculetype, brandvehicule, ptacvehicule, client, depot) VALUES(:val1,:val2,:val3,:val4,:val5,:val6,:val7,:val8)";
                        $req = $bdd->prepare($qu);
                        $req->bindValue(':val1',$radiusdepot, PDO::PARAM_STR);
                        $req->bindValue(':val2',$urlpermis, PDO::PARAM_STR);
                        $req->bindValue(':val3',$urlpoints, PDO::PARAM_STR);
                        $req->bindValue(':val4', $vehiculetype, PDO::PARAM_STR);
                        $req->bindValue(':val5', $vehiculebrand, PDO::PARAM_STR);
                        $req->bindValue(':val6',$vehiculeptac, PDO::PARAM_STR);
                        $req->bindValue(':val7', $_SESSION['id'], PDO::PARAM_INT);
                        $req->bindValue(":val8",$depot,PDO::PARAM_STR);
                        $req->execute();
                        header('Location: ../index.php');
                        exit();
                    }
                }
            }
        }
    }
}
