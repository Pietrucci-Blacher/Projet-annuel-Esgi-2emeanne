<?php
require_once('../user.php');
if(empty($_POST)){
    header('Location: ../../index.php');
    exit();
}

$id = (int)$_POST['userid'];
$bantime = $_POST['bantime'];

if(isset($_POST['userid'])){
    if(isset($_POST['bantime'])){
        banuser($id,$bantime);
    }else{
        http_response_code(500);
    }
}else{
    http_response_code(500);
}