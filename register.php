<?php
require_once('include/connexionbdd.php');
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
$hl = ""; 


$q = "SELECT id FROM client WHERE email = :val1";
$req = $bdd->prepare($q);
$req->bindValue(':val1',$_POST['email'],PDO::PARAM_STR);
$req->execute();
$resultcheck = $req->fetch();
$secretKey = '0x2c2a7110F346623cbe0b87cDDeee1d29a33bA23f';

switch($_COOKIE['language']){
    case "english": 
        $hl = "en"; 
        break;
    case "spanish":
        $hl = "es";
        break; 
    default: 
        $hl = "fr"; 
}

if($resultcheck == 0){
    if(isset($_POST['gender'])){
        if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])){
            if(isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])){
                if(isset($_POST['email']) && $_POST['email'] == $_POST['emailcheck'] && filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                    if(isset($_POST['password']) && isset($_POST['passwordcheck']) && $_POST['password'] == $_POST['passwordcheck'] && preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/",$_POST['password'])){
                        if(isset($_POST['address']) && isset($_POST['city']) && strlen($_POST['address']) > 1 && strlen($_POST['city']) > 1 && is_string($_POST['address']) && is_string($_POST['city'])){
                            if(isset($_POST['zipcode']) && strlen($_POST['zipcode']) == 5 && is_numeric($_POST['zipcode'])){
                                if(isset($_POST['phonenum']) && strlen($_POST['phonenum']) == 10 && is_string($_POST['phonenum'])) {
                                    if(isset($_POST['status']) && isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])){
                                        $verifyURL = 'https://hcaptcha.com/siteverify';
                                        $token = $_POST['h-captcha-response'];
                                        $data = array(
                                             'secret' => $secretKey,
                                             'response' => $token,
                                             'remoteip' => $_SERVER['REMOTE_ADDR']
                                        );
                                        $curlConfig = array(
                                            CURLOPT_URL => $verifyURL,
                                            CURLOPT_POST => true,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_POSTFIELDS => $data
                                        );
                                        $ch = curl_init();
                                        curl_setopt_array($ch, $curlConfig);
                                        $response = curl_exec($ch);
                                        curl_close($ch);
                                        $responseData = json_decode($response);
                                        if($responseData->success){
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
    <script src="https://hcaptcha.com/1/api.js?hl=<?php echo $hl ?>" async defer></script>
    <script src="js/form.js"></script>
    <script src="js/translate.js"></script>
    <title>Ultimate Parcel - Enregistrement</title>
</head>
<body  onCopy="return false" onPaste="return false" onCut="return false">
<?php include('include/header.php'); ?>
<br><br>
<div class="container">
    <div class="col-lg-8 mx-auto">
        <h3 class="text-center fw-bold mb-3" langtrad="1">S'enregistrer</h3>
        <form class="border bg-light border-dark rounded text-align p-4" id="formcheck" method="post" enctype="multipart/form-data">
            <br>
            <div class="d-flex mt-3 justify-content-around">
                <div class="form-group flex-fill mx-2">
                    <select class="form-select" id="gender" name="gender">
                        <option selected langtrad="2">Sexe</option>
                        <option langtrad="3" value="Monsieur">Monsieur</option>
                        <option langtrad="4" value="Madame">Madame</option>
                    </select>
                </div>
                <div class="form-group flex-fill mx-3 px-5">
                    <input type="text" id="lastname" class="form-control" name="lastname" placeholder="Nom" autocomplete="family-name" langtrad="5" autofocus>
                </div>
                <div class="form-group flex-fill mx-4">
                    <input type="text" id="firstname"  class="form-control" name="firstname" placeholder="Prénom" autocomplete="given-name" langtrad="6"><br>
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group ">
                    <input class="form-control" id="email" type="email" name="email" placeholder="Email" aria-describedby="emailHelp" autocomplete="email"><br>
                    <dl>
                        <dt langtrad="7">Le mail doit etre du type : test.test@gmail.com</dt>
                    </dl>
                </div>
                <div class="form-group">
                    <input class="form-control" id="emailcheck" type="email"  name="emailcheck" placeholder="Vérification de l'email saisi" autocomplete="email" langtrad="8">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id="password" type="password"  name="password" placeholder="Mot de Passe" aria-describedby="password" autocomplete="new-password" langtrad="9"><br>
                    <dl>
                        <dt langtrad="10">Le mot de passe doit contenir : </dt>
                        <dd langtrad="11">- Au moins 8 caractères</dd>
                        <dd langtrad="12">- Au moins une lettre miniscule</dd>
                        <dd langtrad="13">- Au moins une lettre majuscule</dd>
                        <dd langtrad="14">- Au moins un chiffre</dd>
                        <dd langtrad="15">- Au moins un caractère</dd>
                    </dl>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"  id="showPassword">
                        <label class="form-check-label" for="showPassword" langtrad="16">Montrer le mot de passe : </label>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <input class="form-control" id ="passwordcheck" type="password"  name="passwordcheck" placeholder="Vérification du Mot de Passe" langtrad="17">
                </div>
            </div>
            <hr class="mx-4">
            <div class="mx-3">
                <div class="form-group">
                    <input class="form-control" id ="address" type="text"  name="address" placeholder="Adresse" autocomplete="street-address" langtrad="18"><br>
                </div>
                <div class="d-flex mt-3 justify-content-around">
                    <div class="form-group flex-fill mx-3">
                        <input class="form-control" id ="city" type="text"  name="city" placeholder="Ville" langtrad="19"><br>
                    </div>
                    <div class="form-group flex-fill mx-3">
                        <input class="form-control" id ="zipcode" type="number"  name="zipcode" placeholder="Code Postal" autocomplete="postal-code" min="0" langtrad="20"><br>
                    </div>
                </div>
                <div class="form-group">
                    <input class="form-control" id ="phonenum" type="tel"  name="phonenum" placeholder="Numéro de Téléphone" langtrad="21"><br>
                </div>
            </div>
            <br>
            <div class="h-captcha text-center" data-sitekey="caa7faa2-2f61-4f97-9a57-1285fdd1007a"></div>
            <span><?php echo $errorcaptcha ?></span>
            <br>
            <hr class="mx-4">
            <div class="form-check mx-3">
                <input class="custom-control-input" type="radio" name="status" id="delivery" value="livreur">
                <label class="form-check-label" for="delivery" langtrad="22">
                    Livreur
                </label>
            </div>
            <div class="form-check mx-3">
                <input class="custom-control-input" type="radio" name="status" id="company" value="entreprise">
                <label class="form-check-label" for="company" langtrad="23">
                    Entreprise
                </label>
            </div>
            <br>
            <div class="form-group mx-5 mb-5">
                <input class="form-control" type="submit" value="Envoyer" langtrad="24">
            </div>
        </form>
    </div>
</div>
<br>
</body>
</html>
