<?php
session_start();

require_once __DIR__ . '/../include/connexionbdd.php';

function EnterprisepushSiret($siret){
    $bdd = connexionBDD();
    $req = $bdd->prepare("INSERT INTO entreprise(numSiret, client) VALUES (:val1,:val2)");
    $req->bindValue(":val1",$siret,PDO::PARAM_STR);
    $req->bindValue(":val2",$_SESSION['id'], PDO::PARAM_INT);
    $req->execute();
}

function getSiretByid($id){
    $bdd = connexionBDD();
    $req = $bdd->prepare("SELECT numSiret FROM entreprise WHERE client = :id");
    $req->bindValue(":id",$id,PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetch(PDO::FETCH_ASSOC);
    return $res['numSiret'];

}