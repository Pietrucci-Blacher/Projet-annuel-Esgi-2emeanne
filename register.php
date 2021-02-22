<!Doctype html>
<html lang="fr" dir="ltr">
<head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <meta name="description" content="Page d'enregistrement de l'application web Ultimate Parcer">
    <meta name="keywords" content="livraison,colis">
    <link href="css/form.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
    <script src="js/form.js"></script>
    <title>Ultimate Parcel - Enregistrement</title>
</head> 
<body  onCopy="return false" onPaste="return false" onCut="return false">
<?php include('include/header.php'); ?>
<br><br>
<div class="row">
    <div class="col-sm-9 col-md-7 col-lg-6 mx-auto">
        <h3 class="text-center mb-3">S'enregistrer</h3>
        <form class="border bg-light border-dark rounded text-align" action="include/registercheck.php" id="formcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="d-flex mt-3 justify-content-around">
                <div class="form-group flex-fill mx-3">
                    <input type="text" id="lastname" class="form-control" name="lastname" placeholder="Nom" autofocus>
                </div>
                <div class="form-group flex-fill mx-3">
                    <input type="text" id="firstname"  class="form-control" name="firstname" placeholder="Prénom"><br>
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3"> 
                <div class="form-group 3">
                    <input class="form-control" id="mail" type="email" name="email" placeholder="Email" aria-describedby="emailHelp"><br>
                    <dl>
                        <dt>Le mail doit etre du type : test@gmail.com</dt>
                    </dl>
                </div>
                <div class="form-group">
                    <input class="form-control" id="mailcheck" type="email"  name="emailcheck" placeholder="Vérification de l'email saisi">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id="passwordinput" type="password"  name="password" placeholder="Mot de Passe" aria-describedby="passwordhelp"><br>
                    <dl>
                        <dt>Le mot de passe doit contenir : </dt>
                        <dd>- Au moins 8 caractères</dd>
                        <dd>- Au moins une lettre miniscule</dd>
                        <dd>- Au moins une lettre majuscule</dd>
                        <dd>- Au moins un chiffre</dd>
                        <dd>- Au moins un caractère</dd>
                    </dl>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"  id="showPassword">
                        <label class="form-check-label" for="showPassword">Montrer le mot de passe : </label>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <input class="form-control" id ="checkpassword" type="password"  name="passwordcheck" placeholder="Vérification du Mot de Passe">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id ="address" type="text"  name="address" placeholder="Adresse"><br>
                </div>
                <div class="form-group">
                    <input class="form-control" id ="city" type="text"  name="city" placeholder="Ville"><br>
                </div>
                <div class="form-group">
                    <input class="form-control" id ="zipcode" type="number"  name="zipcode" placeholder="Code Postal"><br>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="compinfo" id="compinfo" placeholder="Informations complémentaires"></textarea><br>
                </div>
                <div class="form-group">
                    <input class="form-control" id ="phonenum" type="tel"  name="phonenum" placeholder="Numéro de Téléphone"><br>
                </div>
            </div>
            <div class="h-captcha text-center" data-sitekey="fe8fee25-0e9b-484f-b636-1c3ab09614a6"></div><br>
            <div class="form-check">
                <input class="form-check-input" name="cgucheck" type="checkbox" value="" id="defaultCheck">
                <label class="form-check-label" for="defaultCheck">
                    Acceptez-vous les <a href="include/CGU.php">CGU</a> ?
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="newletters" type="checkbox" value="" id="defaultCheck">
                <label class="form-check-label" for="defaultCheck">
                    Souhaitez vous recevoir la newletters ? 
                </label>
            </div>
            <br>
            <div class="form-group mb-5">
                <input class="form-control" type="submit" value="Envoyer">
            </div>
        </form>
    </div>
</div>
</body>
</html>

