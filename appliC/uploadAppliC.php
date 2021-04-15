<?php
/* Les données PUT arrivent du flux */
$putdata = fopen("php://input", "r");
if (isset($putdata)) {
  echo "JE SUIS LA";
}
/* Ouvre un fichier pour écriture */
$fp = fopen($_GET['name'] , "w");

/* Lecture des données, 1 Ko à la fois et écriture dans le fichier */
while ($data = fread($putdata, 1024))
fwrite($fp, $data);

/* Fermeture du flux */
fclose($fp);
fclose($putdata);

?>
