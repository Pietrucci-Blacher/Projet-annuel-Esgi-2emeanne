<?php
	try{
		$bdd = new PDO('mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_a4b01b2a0b88f60','bdd1b420797f42','190eb870', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	}catch(Exception $e){
		die('Erreur : ' . $e->getMessage());
	}
?>
