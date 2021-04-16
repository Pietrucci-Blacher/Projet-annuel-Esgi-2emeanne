<?php
require_once('../user.php');

$permis = (bool)$_POST['perm'];
$user = (int)$_POST['userid'];

if(isset($_POST['perm']) && isset($_POST['userid'])){
    permischeckuser($permis,$user);
}else{
    http_response_code(500);
}
