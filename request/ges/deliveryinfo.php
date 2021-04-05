<?php
require_once('../user.php');
if(empty($_POST)){
    header('Location: ../../index.php');
    exit();
}


$userid = (int)$_POST['userid'];
if($userid > 0){
    echo json_encode(getDeliveryData($userid)); //Faire passser les informations livreurs et les additioner à celle de l'autre JSON
}
