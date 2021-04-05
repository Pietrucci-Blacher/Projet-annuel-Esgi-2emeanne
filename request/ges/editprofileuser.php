<?php
require_once('../user.php');
$userid = (int)$_POST['userid'];

if(empty($_POST)){
    header('Location: ../../index.php');
    exit();
}

if($userid > 0){
    echo json_encode(getAdminDatabyid($userid));
}