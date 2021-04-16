<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //import du sdk
    require('cloudinary/Cloudinary.php');
    require('cloudinary/Uploader.php');
    require('cloudinary/Api.php');

    //configuration des clés API
    \Cloudinary::config(array(
    "cloud_name" => "hvrzhzxky",
    "api_key" => "812238978328538",
    "api_secret" => "_TOLF-WD9wC2a1kBHDDDGGTAHAg"
    ));

    //upload
    $result = \Cloudinary\Uploader::upload("test.jpg");

    //récupération de l'url (à stocker dans la bdd)
    echo $result['url'];

    ?>
  </body>
</html>
