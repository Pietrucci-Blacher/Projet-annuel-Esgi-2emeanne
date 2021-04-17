<?php
/* Les données PUT arrivent du flux */
$putdata = fopen("php://input", "r");

if (isset($putdata)) {
  echo "JE SUIS LA";
}

$fp = fopen("new.csv" , "w");

/* Lecture des données, 1 Ko à la fois et écriture dans le fichier */
while ($data = fread($putdata, 1024))
fwrite($fp, $data);

fclose($putdata);
fclose($fp);

require('../cloudinary/Cloudinary.php');
require('../cloudinary/Uploader.php');
require('../cloudinary/Error.php');
require('../cloudinary/Api.php');

//configuration des clés API
\Cloudinary::config(array(
"cloud_name" => "hvrzhzxky",
"api_key" => "812238978328538",
"api_secret" => "_TOLF-WD9wC2a1kBHDDDGGTAHAg"
));

//upload
\Cloudinary\Uploader::upload("new.csv",["resource_type" => "auto", "folder" => "uploadExcell"]);

?>
