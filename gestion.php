<?php
ini_set('display_errors',1);
require_once(__DIR__ . '/request/user.php');
require_once('include/connexionbdd.php');
if(getUserStatus($_SESSION['id']) != "admin" || !isset($_SESSION)){
    header('Location: index.php');
    exit();
}

$lastname = htmlspecialchars(trim($_POST['lastname']));
$firstname = htmlspecialchars(trim($_POST['firstname']));
$email = htmlspecialchars(trim($_POST['email']));
$address = htmlspecialchars(trim($_POST['address']));
$zipcode = htmlspecialchars(trim($_POST['zipcode']));
$city = htmlspecialchars(trim($_POST['city']));
$phonenum = htmlspecialchars(trim($_POST['phonenum']));
$status = $_POST['status'];

if(isset($_POST['lastname']) && strlen($_POST['lastname']) >= 1 && strlen($_POST['lastname']) <= 70 && is_string($_POST['lastname'])) {
    if (isset($_POST['firstname']) && strlen($_POST['firstname']) >= 1 && strlen($_POST['firstname']) <= 60 && is_string($_POST['firstname'])) {
        if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            if(isset($_POST['address']) && isset($_POST['city']) && strlen($_POST['address']) > 1 && strlen($_POST['city']) > 1 && is_string($_POST['address']) && is_string($_POST['city'])) {
                echo 'ok';
                if(isset($_POST['zipcode']) && strlen($_POST['zipcode']) == 5 && is_numeric($_POST['zipcode'])) {
                    echo 'ok';
                    if(isset($_POST['address']) && isset($_POST['city']) && strlen($_POST['address']) > 1 && strlen($_POST['city']) > 1 && is_string($_POST['address']) && is_string($_POST['city'])){
                        echo 'ok';
                        if(isset($_POST['phonenum']) && strlen($_POST['phonenum']) == 10 && is_string($_POST['phonenum'])) {
                            if(isset($_POST['status'])){
                                echo "ok";
                                UpdateUserinfo($lastname,$firstname,$email,$zipcode,$city,$phonenum,$status,$_SESSION['id']);
                                header('Refresh:0');
                                exit();
                            }
                        }
                    }
                }
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <?php require_once('include/head.php'); ?>
        <?php require_once('include/script.php'); ?>
        <link rel="stylesheet" href="css/index.css" type="text/css">
        <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@bf7775b/css/all.css" rel="stylesheet" type="text/css" />
        <meta name="description" content="Page de gestion du site web Ultimate Parcer">
        <meta name="keywords" content="livraison,colis">
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
        <script src="js/formgestion.js"></script>
        <title>Ultimate Parcel - Gestion du site </title>
    </head>
    <?php require_once('include/header.php'); ?>
    <body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-9 mx-auto" style="background-color: white;">
                <h1 class="text-center fw-bold my-3">Gestion du site</h1>
                <hr>
                <h2 class="text-center">Gestion des utilisateurs</h2>
                <div>
                <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ville</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <?php
                    $uinfos = getAllData();
                    foreach ( $uinfos as $user) {?>
                        <tr>
                            <th><?php echo $user['id']; ?></th>
                            <td><?php echo $user['nom']; ?></td>
                            <td><?php echo $user['prenom'];?></td>
                            <td><?php echo $user['status'];?></td>
                            <td><?php echo $user['email'];?></td>
                            <td><?php echo $user['ville'];?></td>
                            <td>
                                <button user-id="<?php echo $user['id']?>" type="button" title="Modifier le compte" class="btn btn-success edituser"><i class="far fa-edit"></i></button>
                                <button user-id="<?php echo $user['id']?>" type="button" title="Bannir le compte" class="btn btn-warning banuser"data-bs-toggle="modal" data-bs-target="#banuserid"> <span style="color: white;"><i class="fas fa-gavel"></span></i></i></button>
                                <button user-id="<?php echo $user['id']?>" type="button" title="Supprimer le compte" class="btn btn-danger deleteuser"><i class="far fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    <?php } ;  ?>
                </table>
                <br>
                <hr class="mx-4">
                <br>
                <span id="modifyzone">
                    <div class="container">
                        <div class="border border-light rounded">
                            <br>
                            <form method="post" enctype="multipart/form-data" id="updateinfoform">
                               <div class="input-group">
                                   <label class="m-2"  for="lastname">Nom :</label>
                                   <input type="text" class="form-control mx-3" id="lastname" name="lastname" aria-describedby="lastname" placeholder="Nom">
                                   <label class="m-2"  for="firstname">Prénom :</label>
                                   <input type="text" class="form-control mx-3" name="firstname" id="firstname" aria-describedby="firstname" placeholder="Prénom">
                                   <label class="m-2"  for="status">Statut :</label>
                                   <select class="form-select mx-3" name="status" id="status">
                                       <option value="admin">Administrateur</option>
                                       <option value="entreprise">Entreprise</option>
                                       <option value="livreur">Livreur</option>
                                       <option value="client">Client</option>
                                   </select>
                               </div>
                               <br>
                                <div class="input-group">
                                    <label class="m-2" for="address">Adresse :</label>
                                    <input type="text" class="form-control mx-4" name="address" id="address" aria-describedby="address" placeholder="Adresse">
                                </div>
                                <br>
                                <div class="input-group">
                                   <label class="m-2" for="city">Ville :</label>
                                   <input type="text" class="form-control mx-3" name="city" id="city" aria-describedby="city" placeholder="Ville">
                                   <label class="m-1"  for="zipcode">Code Postal :</label>
                                   <input type="number" min="0" minlength="5" maxlength="5" class="form-control mx-3" name="cpostal" id="cpostal" aria-describedby="codepostale" placeholder="Code Postale">
                                   <label class="m-1" for="tel">Numéro Téléphone :</label>
                                   <input class="form-control mx-3" type="number" maxlength="10" minlength="10" name="phonenum" id="phonenum" aria-describedby="telephone"  placeholder="Numéro de téléphone">
                               </div>
                               <br>
                               <div class="input-group" id="bandiv">
                                   <label class="m-2" for="email">Email :</label>
                                   <input type="email" class="form-control mx-3" name="email" id="email" aria-describedby="email" placeholder="Email">
                                   <label class="m-2">Banni : </label>
                                   <input class="form-control text-center fw-bold" name="banacount" id="banacount"  aria-describedby="banaccount" disabled>
                               </div>
                               <br>
                               <div id="deliveryzone"></div>
                               <br>
                               <div class="text-center">
                                   <button type="submit" class="btn btn-success ">Valider les données</button>
                               </div>
                            </form>
                            <br>
                            <div class="">
                                <h4>Lien vers le permis</h4>
                                <a id="drivinglicence" target="_blank">Visualiser le nombre de points sur le permis</a>
                                <h4>Lien vers le nombre de points</h4>
                                <a id="pointslicence" target="_blank">Visualiser le nombre de points sur le permis</a>
                                <form class="form-check form-switch" method="post" enctype="multipart/form-data">
                                    <input type="checkbox" class="form-check-input" id="permval" name="permval">
                                    <label for="permval">Valider le permis</label>
                                </form>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
            </div>
            <div class="modal fade" id="banuserid" tabindex="-1" aria-labelledby="banuserid" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Souhaitez vous réellement bannir cet utilisateur ?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#inputbanuser">Oui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="inputbanuser" tabindex="-1" aria-labelledby="inputbanuser" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Veuillez insérer le temps de bannissement (en jours) (1000 ou plus pour bannissement permanent) </h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <input class="form-control my-2"  type="datetime-local" name="bantime" id="bantime">
                        </div>
                        <div class="modal-footer">
                                <button class="btn btn-primary banuser" type="submit" data-bs-dismiss="modal">Valider</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
