<?php
require_once(__DIR__ . '/request/user.php');
require_once('include/utilities/banuser.php');
checkbanuser();
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
$zonegeo = $_POST['geozone'];
$brandvehicule = $_POST['brandvehicule'];
$vehiculetype = $_POST['vehiculetype'];
$ptac = $_POST['ptacvehicule'];

if(isset($_POST) && !empty($_POST)){
    if($status == "livreur") {
        Updatedeliverinfo($email, $zonegeo, $brandvehicule, $vehiculetype, $ptac);
    }
    UpdateUserinfo($lastname,$firstname,$email,$address,$zipcode,$city,$phonenum,$status);
    header('Refresh:0');
    exit();
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
        <meta charset="utf-8">
        <meta name="keywords" content="livraison,colis">
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
                <br>
                <div>
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Actions</th>
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
                                   <input type="number" min="0" minlength="5" maxlength="5" class="form-control mx-3" name="zipcode" id="zipcode" aria-describedby="codepostale" placeholder="Code Postale" min="0" max="99999">
                                   <label class="m-1" for="tel">Numéro Téléphone :</label>
                                   <input class="form-control mx-3" type="number" maxlength="10" minlength="10" name="phonenum" id="phonenum" aria-describedby="telephone"  placeholder="Numéro de téléphone">
                               </div>
                               <br>
                               <div class="input-group" id="bandiv">
                                   <label class="m-2" for="email">Email :</label>
                                   <input type="email" class="form-control mx-3" name="email" id="email" aria-describedby="email" placeholder="Email">
                                   <label class="m-2">Banni : </label>
                                   <input class="form-control text-center fw-bold" name="banacount" id="banacount"  aria-describedby="banaccount" disabled>
                                   <label class='m-2' for='bantime' id='labeltime'>Temps de ban :</label>
                                   <input class='form-control mx-3 bantime text-center fw-bold' name='bantime' id='bantime' type='datetime-local' disabled>
                               </div>
                               <br>
                               <div id="deliveryzone">
                               <hr>
                                    <div class='input-group'>
                                        <label class='m-2' for='brandvehicule'>Marque du véhicule : </label>
                                        <input type='text' class='form-control mx-3' name='brandvehicule' id='brandvehicule' aria-describedby='brandvehicule' placeholder='Marque du Véhicule'>
                                        <label class='m-2' for='ptacvehicule'>PTAC du Véhicule : </label>
                                        <input class='form-control mx-3' name='ptacvehicule' id='ptacvehicule' aria-describedby='ptacvehicule' placeholder='PTAC du véhicule'>
                                        <label class='m-2' for='vehiculetype'>Type de vehicule : </label>
                                        <input class='form-control mx-3' name='vehiculetype' id='vehiculetype' aria-describedby='vehiculetype' placeholder='Type du véhicule'>
                                    </div>
                                    <br>
                                    <div class='input-group'>
                                    <div class='input-group'>
                                        <label class='m-2' for='geozone'>Dépôt :</label>
                                        <select class="form-select" id="depot" name="depot">
                                            <?php
                                            $depots = getdepots();
                                            foreach($depots as $depot){ ?>
                                                echo "<option value="<?php echo $depot['id'] ?>"><?php echo $depot['adresse'] ?> - <?php echo $depot['ville'] ?> </option>
                                            <?php } ?>
                                        </select>
                                        <label class='m-2' for='radiusdepot'>Distance max de livraison autour du dépot :</label>
                                           <select class="form-select" id="radiusdepot" name="radiusdepot">
                                               <?php
                                               for($i = 30; $i <=300; $i += 10){
                                                   echo "<option value='$i'>". $i . " km" ."</option>";
                                               } ?>
                                           </select>
                                    </div>
                                    </div>
                               </div>
                               <br>
                               <div class="text-center">
                                   <button type="submit" class="btn btn-success ">Valider les données</button>
                               </div>
                            </form>
                            <br>
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
                            <h5 class="modal-title text-center" id="modalbantext">Veuillez insérer le temps de bannissement (en jours)</h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data">
                                <input class="form-control my-2"  type="datetime-local" name="bantimeinp" id="bantimeinp">
                        </div>
                        <div class="modal-footer">
                                <button class="btn btn-primary banuserval" type="submit" data-bs-dismiss="modal">Valider</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
