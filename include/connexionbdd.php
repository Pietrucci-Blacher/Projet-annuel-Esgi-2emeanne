<?php
require_once('include/variable.php');

function connexionBDD(){
	try{
		$bdd = new PDO(driver.":host=".host.";dbname=".dbname,user,pwd, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
		return $bdd;
	}catch(Exception $e){
		die('Erreur : ' . $e->getMessage());
	}

}
?>
