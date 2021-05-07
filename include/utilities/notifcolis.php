<?php
require_once('request/user.php');
require_once('include/connexionbdd.php');

function checkPackage($packageid){
    $bdd = connexionBDD();
    $date = date("Y-m-d");
    $q = "SELECT date,client FROM colis WHERE refQrcode = :id";
    $req = $bdd->prepare($q);
    $req->bindValue(":id",$packageid,PDO::PARAM_INT);
    $res = $req->fetch(PDO::FETCH_ASSOC);
    $colischeck = $res['date'];
    updateCheckpackage($res['client']);
    if($colischeck == $date){
        sendMessage($res['client'],$packageid);
    }
}

function SendMessage($userid , $packageid){
    $message = array(
        'en' => "Your parcel : " . $packageid . " is coming today",
        'es' => "Su paquete : "  . $packageid ."llega hoy",
        'fr' => "Votre colis : " . $packageid . "arrive aujourd'hui"
    );
    $datas = array(
        'app_id' => "d521950b-19d2-4a25-a4bc-c341fc116892",
        'contents' => $message,
        'data' => array("foo" => "bar")
    );

    $fields = json_encode($datas);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($curl, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic YjA1MDRjMzAtYzFhNi00OTVhLWIxMTItMTA0ZDk5MjM0NDc2'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_PROXY_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($curl);
    curl_close($curl);
}


