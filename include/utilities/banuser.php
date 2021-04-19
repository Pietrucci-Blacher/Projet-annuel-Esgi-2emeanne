<?php
require_once(__DIR__ . '/../../request/user.php');
require_once(__DIR__ . '/../connexionbdd.php');

if(!isset($_POST)){
    header('Location: ../../index.php');
    exit();
}

function checkbanuser(){
    if(isset($_SESSION['id'])){
        $date = new DateTime();
        $ban = getBaninfouser($_SESSION['id']);
        $bantime = new DateTime($ban[0]['bantime']);
        if( $ban[0]['bannedAcount'] == 1 && isset($ban[0]['bantime'])){
            if($date >= $bantime ){
                updateBaninfo();
            }else{
                header('Location: logout.php');
                exit();
            }
        }
    }
}