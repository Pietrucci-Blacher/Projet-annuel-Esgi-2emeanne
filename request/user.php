<?php
session_start();
require_once __DIR__ . '/../include/connexionbdd.php';

function getData(){
    $bdd = connexionBDD();
    $res = $bdd -> prepare("SELECT genre, nom, prenom, mdp, status, adresse, ville, codePostal, email, numPhone FROM client WHERE id = :id");
    $res->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
    $res->execute();
    $uinfo = $res->fetchAll(PDO::FETCH_ASSOC);
    return $uinfo;

}
