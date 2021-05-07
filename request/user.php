<?php
session_start();

require_once __DIR__ . '/../include/connexionbdd.php';

function getData(){
    $bdd = connexionBDD();
    $res = $bdd -> prepare("SELECT id, genre, nom, prenom, mdp, status, adresse, ville, codePostal, email, numPhone FROM client WHERE id = :id");
    $res->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $res->execute();
    $uinfo = $res->fetchAll(PDO::FETCH_ASSOC);
    return $uinfo;
}

function checkfirstconnect(){
    $bdd = connexionBDD();
    $q = "SELECT Firstconnect from client WHERE id = :id";
    $req = $bdd->prepare($q);
    $req->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
    $bool = $req->fetch(PDO::FETCH_ASSOC);
    return $bool["Firstconnect"];
}

function getAllData(){
    $bdd = connexionBDD();
    $res = $bdd -> prepare("SELECT id, genre, nom, prenom, status, ville, codePostal, email, numPhone FROM client");
    $res->execute();
    $dinfo = $res->fetchAll(PDO::FETCH_ASSOC);
    return $dinfo;
}

function getAdminDatabyid($id){
    $bdd = connexionBDD();
    $res = $bdd -> prepare("SELECT id,nom,prenom,status,adresse,ville,codePostal,email,numphone,bannedAcount,bantime,birthdate FROM client WHERE id = :id");
    $res->bindValue(":id", $id, PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

function deleteuserbyID($id){
    $bdd = connexionBDD();
    $res = $bdd->prepare("DELETE FROM colis WHERE client = :id_user");
    $res->bindValue(":id_user",$id,PDO::PARAM_INT);
    $res->execute();
    $res = $bdd->prepare("DELETE FROM entreprise WHERE client = :id_user");
    $res->bindValue(":id_user",$id,PDO::PARAM_INT);
    $res->execute();
    $res = $bdd->prepare("DELETE FROM livreur WHERE client = :id_user");
    $res->bindValue(":id_user",$id,PDO::PARAM_INT);
    $res->execute();
    $res = $bdd->prepare("DELETE FROM client WHERE id = :id_user");
    $res->bindValue(":id_user",$id,PDO::PARAM_INT);
    $res->execute();
}

function getDeliveryData($id){
    $bdd = connexionBDD();
    $res = $bdd->prepare("SELECT zoneGeo,nbKm,etatPermis,lienPermis,brandvehicule,ptacvehicule,vehiculetype, depot FROM livreur WHERE client = :id_user");
    $res->bindValue(":id_user",$id,PDO::PARAM_INT);
    $res->execute();
    return $res->fetch(PDO::FETCH_ASSOC);
}

function banuser($id, $time){
    $bdd = connexionBDD();
    $res = $bdd->prepare("UPDATE client SET bannedAcount = TRUE, bantime = :bantime WHERE id = :id");
    $res->bindValue(":bantime",$time,PDO::PARAM_STR);
    $res->bindValue(":id",$id,PDO::PARAM_INT);
    $res->execute();
}

function getUserStatus($id){
    $bdd = connexionBDD();
    $res = $bdd->prepare("SELECT status FROM client WHERE id = :id");
    $res->bindValue(":id", $id, PDO::PARAM_INT);
    $res->execute();
    $status = $res->fetch(PDO::FETCH_ASSOC);
    return $status['status'];
}

function UpdateUserinfo($lastname,$firstname,$email,$address,$zipcode,$city,$phonenum,$status, $birthdate){
    $bdd = connexionBDD();
    $res = $bdd->prepare("SELECT id FROM client WHERE email = :email");
    $res->bindValue(":email",$email,PDO::PARAM_STR);
    $res->execute();
    $uid = $res->fetch(PDO::FETCH_ASSOC);
    $uidf = $uid['id'];

    $req = $bdd->prepare("UPDATE client SET nom  = :nom, prenom = :prenom, email = :email, adresse = :addr, codePostal = :cp, ville = :city, numPhone = :num, status = :status, birthdate = :birth WHERE id = :id");
    $req->bindValue(":nom",$lastname,PDO::PARAM_STR);
    $req->bindValue(":prenom",$firstname,PDO::PARAM_STR);
    $req->bindValue(":email",$email,PDO::PARAM_STR);
    $req->bindValue(":addr",$address,PDO::PARAM_STR);
    $req->bindValue(":cp",$zipcode,PDO::PARAM_STR);
    $req->bindValue(":city",$city,PDO::PARAM_STR);
    $req->bindValue(":num",$phonenum,PDO::PARAM_STR);
    $req->bindValue(":status",$status,PDO::PARAM_STR);
    $req->bindValue(":birth",$birthdate,PDO::PARAM_STR);
    $req->bindValue(":id",$uidf,PDO::PARAM_STR);
    $req->execute();
}

function updateCheckpackage($id){
    $bdd = connexionBDD();
    $req = $bdd->prepare("UPDATE client SET checkpackage = TRUE WHERE id = :id");
    $req->bindValue(":id",$id,PDO::PARAM_INT);
    $req->execute();
}

function permischeckuser($permis,$user){
    $bdd = connexionBDD();
    $req = $bdd->prepare("UPDATE livreur SET validatedperm = :bool WHERE client = :id");
    $req->bindValue(":bool",$permis,PDO::PARAM_STR);
    $req->bindValue(":id",$user,PDO::PARAM_INT);
    $req->execute();
}

function Updatedeliverinfo($email, $geozone,$brand,$vehiculetype,$ptac, $depot){
    $bdd = connexionBDD();
    $res = $bdd->prepare("SELECT id FROM client WHERE email = :email");
    $res->bindValue(":email",$email,PDO::PARAM_STR);
    $res->execute();
    $uid = $res->fetch(PDO::FETCH_ASSOC);
    $uidf = $uid['id'];

    $req = $bdd->prepare("UPDATE livreur SET zoneGeo = :geo, vehiculetype = :type, brandvehicule = :brand, ptacvehicule = :ptac, depot = :depot WHERE client = :id");
    $req->bindValue(":geo",$geozone,PDO::PARAM_STR);
    $req->bindValue(":type",$vehiculetype,PDO::PARAM_STR);
    $req->bindValue(":brand",$brand,PDO::PARAM_STR);
    $req->bindValue(":ptac",$ptac,PDO::PARAM_STR);
    $req->bindValue(":depot", $depot, PDO::PARAM_STR);
    $req->bindValue(":id",$uidf,PDO::PARAM_STR);
    $req->execute();
}

function getalldeliverydata(){
    $bdd = connexionBDD();
    $res = $bdd->prepare("SELECT zoneGeo,nbKm,etatPermis,lienPermis,client,validatedperm,client.prenom, client.nom FROM livreur INNER JOIN client ON livreur.client = client.id");
    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

function getBaninfouser($id){
    $bdd = connexionBDD();
    $q = 'SELECT bannedAcount,bantime FROM client WHERE id = :id';
    $req = $bdd->prepare($q);
    $req->bindValue(":id",$id,PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function updateBaninfo(){
    $bdd = connexionBDD();
    $q = 'UPDATE client SET bannedAcount = FALSE, bantime = NULL WHERE id = :id';
    $req = $bdd->prepare($q);
    $req->bindValue(":id",$_SESSION['id'],PDO::PARAM_INT);
    $req->execute();
}


function getdepots(){
    $bdd = connexionBDD();
    $req = $bdd->prepare("SELECT id, adresse, ville FROM depot");
    $req->execute();
    $res = $req->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}
