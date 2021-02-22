<!Doctype html>
<html lang="fr" dir="ltr">
<head>
<?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <meta name="description" content="Page de connexion de l'application web Ultimate Parcer">
    <meta name="keywords" content="livraison,colis">
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="js/formconnect.js"></script>
    <title>Ultimate Parcel - Connexion</title>
</head>
<body>
<?php require_once('include/header.php'); ?>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body bg-light">
            <h5 class="card-title text-center mb-3">Se Connecter</h5>
            <form class="col-md-12" id="formconnectcheck" action="include/signincheck.php" method="POST" enctype="multipart/form-data">
            <div class="form-group mb-2">
                <input name="email" class="form-control" type="email" placeholder="E-Mail" autofocus><br>
            </div>
            <div class="form-group mb-2">
                <input id="password" name="password" class="form-control" type="password" placeholder="Mot de passe"><br>
            </div>
            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="showpassword">
                <label class="custom-control-label" for="customCheck1">Afficher le mot de passe</label>
            </div>
            <hr>
            <div class="custom-control custom-checkbox mb-4">
              <input name="remember" type="checkbox" class="custom-control-input" id="customCheck1">
              <label class="custom-control-label" for="customCheck1">Se souvenir de moi</label>
            </div>
            <hr>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary">Envoyer les données</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
