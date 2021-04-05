<?php
require_once('../user.php');
if(empty($_POST)){
    header('Location: ../../index.php');
    exit();
}


$id = (int)$_POST['userid'];
if(isset($_POST['userid'])){
    deleteuserbyID($id);
}else{
    echo "Unknow parameters";
}
