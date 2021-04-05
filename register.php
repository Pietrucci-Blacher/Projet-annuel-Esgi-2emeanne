<?php
require_once('include/connexionbdd.php');
require_once('include/functions.php');
$bdd = connexionBDD();

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$email = htmlspecialchars(trim($_POST['email']));
$password = sha1(htmlspecialchars(trim($_POST['password'])));
$address = htmlspecialchars(trim($_POST['address']));
$zipcode = htmlspecialchars(trim($_POST['zipcode']));
$city = htmlspecialchars(trim($_POST['city']));
$phonenum = htmlspecialchars(trim($_POST['phonenum']));
$gender = $_POST['gender'];
$status = $_POST['status'];
$token = $_POST['h-captcha-response'];

$q = "SELECT id FROM client WHERE email = :val1";
$req = $bdd->prepare($q);
$req->bindValue(':val1',$_POST['email'],PDO::PARAM_STR);
$req->execute();
$resultcheck = $req->fetch();

// Faire la vérification de  l'adresse et de l'adresse postale  via Curl$handle = curl_init();

if($resultcheck == 0){
    if(isset($_POST['gender'])){
        if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
            if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])){
                if(isset($_POST['email']) && $_POST['email'] == $_POST['emailcheck'] && filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                    if(isset($_POST['password']) && isset($_POST['passwordcheck']) && $_POST['password'] == $_POST['passwordcheck'] && preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/",$_POST['password'])){
                        if(isset($_POST['address']) && isset($_POST['city']) && strlen($_POST['address']) > 1 && strlen($_POST['city']) > 1 && is_string($_POST['address']) && is_string($_POST['city'])){
                            if(isset($_POST['zipcode']) && strlen($_POST['zipcode']) == 5 && is_numeric($_POST['zipcode'])){
                                if(isset($_POST['phonenum']) && strlen($_POST['phonenum']) == 10 && is_string($_POST['phonenum'])) {
                                    if(isset($_POST['status']) && isset($_POST['h-captcha-response'])){
                                        $response = getcaptchareponse($token);
                                        echo $response; 
                                        if($response == true){
                                            $q = 'INSERT INTO client(genre,nom, prenom, email, mdp, status, adresse, ville, codePostal, numPhone) VALUES (:val1,:val2,:val3,:val4,:val5,:val6,:val7,:val8, :val9, :val10)';
                                            $req = $bdd->prepare($q);
                                            $req->bindValue(":val1",$gender,PDO::PARAM_STR);
                                            $req->bindValue(":val2", $lastname, PDO::PARAM_STR);
                                            $req->bindValue(":val3", $firstname, PDO::PARAM_STR);
                                            $req->bindValue(":val4", $email, PDO::PARAM_STR);
                                            $req->bindValue(":val5", $password, PDO::PARAM_STR);
                                            $req->bindValue(":val6", $status, PDO::PARAM_STR);
                                            $req->bindValue(":val7", $address, PDO::PARAM_STR);
                                            $req->bindValue(":val8", $city, PDO::PARAM_STR);
                                            $req->bindValue(":val9", $zipcode, PDO::PARAM_STR);
                                            $req->bindValue(":val10", $phonenum, PDO::PARAM_STR);
                                            $req->execute();
                                            header('Location: index.php');
                                            exit();
                                        }else{
                                            $errorcaptcha = "Veuillez recommencer le captcha";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<!Doctype html>
<html lang="fr" dir="ltr">
<head>
    <?php require_once('include/head.php'); ?>
    <?php require_once('include/script.php'); ?>
    <meta name="description" content="Page d'enregistrement de l'application web Ultimate Parcer">
    <meta name="keywords" content="livraison,colis">
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
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <h3 class="text-center fw-bold mb-3">S'enregistrer</h3>
        <form class="border bg-light border-dark rounded text-align" id="formcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="d-flex mt-3 justify-content-around">
                <div class="form-group flex-fill mx-2">
                    <select class="form-select" id="gender" name="gender">
                        <option selected>Sexe</option>
                        <option value="Monsieur">Monsieur</option>
                        <option value="Madame">Madame</option>
                    </select>
                </div>
                <div class="form-group flex-fill mx-3 px-5">
                    <input type="text" id="lastname" class="form-control" name="lastname" placeholder="Nom" autocomplete="family-name" autofocus>
                </div>
                <div class="form-group flex-fill mx-4">
                    <input type="text" id="firstname"  class="form-control" name="firstname" placeholder="Prénom" autocomplete="given-name"><br>
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group ">
                    <input class="form-control" id="email" type="email" name="email" placeholder="Email" aria-describedby="emailHelp" autocomplete="email"><br>
                    <dl>
                        <dt>Le mail doit etre du type : test.test@gmail.com</dt>
                    </dl>
                </div>
                <div class="form-group">
                    <input class="form-control" id="emailcheck" type="email"  name="emailcheck" placeholder="Vérification de l'email saisi" autocomplete="email">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id="password" type="password"  name="password" placeholder="Mot de Passe" aria-describedby="password" autocomplete="new-password"><br>
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
                    <input class="form-control" id ="passwordcheck" type="password"  name="passwordcheck" placeholder="Vérification du Mot de Passe">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id ="address" type="text"  name="address" placeholder="Adresse" autocomplete="street-address"><br>
                </div>
                <div class="d-flex mt-3 justify-content-around">
                    <div class="form-group flex-fill mx-3">
                        <input class="form-control" id ="city" type="text"  name="city" placeholder="Ville"><br>
                    </div>
                    <div class="form-group flex-fill mx-3">
                        <input class="form-control" id ="zipcode" type="number"  name="zipcode" placeholder="Code Postal" autocomplete="postal-code" min="0"><br>
                    </div>
                </div>
                <div class="form-group">
                    <input class="form-control" id ="phonenum" type="tel"  name="phonenum" placeholder="Numéro de Téléphone"><br>
                </div>
            </div>
            <br>
            <div id="captcha" name="captcha" class="h-captcha text-center" data-sitekey="75ae82a3-a741-4f26-9eec-db8201d34794">
                <span><?php echo $errorcaptcha ?></span>
            </div><br>
            <hr class="mx-4">
            <div class="form-check mx-3">
                <input class="custom-control-input" type="radio" name="status" id="delivery" value="livreur">
                <label class="form-check-label" for="delivery">
                    Livreur
                </label>
            </div>
            <div class="form-check mx-3">
                <input class="custom-control-input" type="radio" name="status" id="company" value="entreprise">
                <label class="form-check-label" for="company">
                    Entreprise
                </label>
            </div>
            <br>
            <div class="form-group mx-5 mb-5">
                <input class="form-control" type="submit" value="Envoyer">
            </div>
        </form>
    </div>
</div>
<br>
</body>
</html>

