<?php
session_start();

$_SESSION['idBill']=$_POST['idBill'];
echo $_SESSION['idBill'];
 ?>
