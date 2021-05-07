<?php
require_once('include/utilities/banuser.php');
require_once('include/utilities/notifcolis.php'); 
checkbanuser();
SendMessage(8,1234);  
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <script src="https://cdn-webgl.wrld3d.com/wrldjs/dist/latest/wrld.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.1/leaflet.css" rel="stylesheet" />
    <script src="js/translate.js"></script>
  </head>
  <?php require_once('include/header.php'); ?>
  <body>
    <div class="container">
      <div id="carouselExampleIndicators" class="carousel slide mt-5 carousel-fade" data-bs-ride="carousel">
        <ol class="carousel-indicators">
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="asset/slider1.png" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <h1 class="pb-5 textslider" langtrad="COMPANYQ">Vous êtes une entreprise ?</h1>
              <p class="mt-5 pb-5 h5 textslider">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
              <div class="d-grid gap-2 col-5 mx-auto">
                <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="location.href='register.php'"><a class="serviceslink" title="Redirection vers le menu d'enregistrement" langtrad="REGISTER">S'INSCRIRE</a></button>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="asset/slider2.png" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <h1 class="pb-5 textslider" langtrad="DELI">Vous êtes un livreur indépendant ?</h1>
              <p class="mt-5 pb-5 h5 textslider">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
              <div class="d-grid gap-2 col-5 mx-auto">
                <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="location.href='register.php'"><a class="serviceslink" langtrad="REGISTER">S'INSCRIRE</a></button>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img src="asset/slider3.png" class="d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
              <h1 class="pb-5 textslider" langtrad="APACKAGE">Vous attendez un colis ?</h1>
              <p class="mt-5 pb-5 h5 textslider">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
              <div class="d-grid gap-2 col-5 mx-auto">
                <button type="button" class="btn btn-primary mt-5 btn-lg" onclick="location.href='parcelTracking.php'"><a class="serviceslink" langtrad="VPACKAGE">SUIVRE MON COLIS</a></button>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </a>
      </div>
    </div>
    <div class="mt-5 banner">
      <div class="container">
        <br>
        <h1 class="banner-item text-center">Ultimate Parcel</h1>
        <br>
        <p class="banner-item text-center h5">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <br>
      </div>
    </div>
    <div class="container mt-5 mb-5">
      <div class="webgl">
          <div class="w-100 h-100" id="map"></div>
      </div>
    </div>
  </body>
</html>
