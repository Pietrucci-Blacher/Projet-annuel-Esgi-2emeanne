<?php
require_once('../user.php');
if(empty($_POST)){
    header('Location: ../../index.php');
    exit();
}

$id = (int)$_POST['useridban'];
$bantime = $_POST['bantime'];

if(isset($_POST['useridban'])){
    if(isset($_POST['bantime'])){
        banuser($id,$bantime);
    }else{
        http_response_code(500);
    }
}else{
    http_response_code(500);
}
