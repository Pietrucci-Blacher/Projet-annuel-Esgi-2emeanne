<?php
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
                                        <label class='m-2' for='geozone'>Zone Géographique :</label>
                                        <select class="form-select mx-3" name="geozone" id="geozone" aria-describedby="geozone">
                                            <option value="01">&#40;01&#41; Ain </option>
                                            <option value="02">&#40;02&#41; Aisne </option>
                                            <option value="03">&#40;03&#41; Allier </option>
                                            <option value="04">&#40;04&#41; Alpes de Haute Provence </option>
                                            <option value="05">&#40;05&#41; Hautes Alpes </option>
                                            <option value="06">&#40;06&#41; Alpes Maritimes </option>
                                            <option value="07">&#40;07&#41; Ardèche </option>
                                            <option value="08">&#40;08&#41; Ardennes </option>
                                            <option value="09">&#40;09&#41; Ariège </option>
                                            <option value="10">&#40;10&#41; Aube </option>
                                            <option value="11">&#40;11&#41; Aude </option>
                                            <option value="12">&#40;12&#41; Aveyron </option>
                                            <option value="13">&#40;13&#41; Bouches du Rhône </option>
                                            <option value="14">&#40;14&#41; Calvados </option>
                                            <option value="15">&#40;15&#41; Cantal </option>
                                            <option value="16">&#40;16&#41; Charente </option>
                                            <option value="2A">&#40;2A&#41; Corse du Sud </option>
                                            <option value="41">&#40;41&#41; Loir et Cher </option>
                                            <option value="51">&#40;51&#41; Marne </option>
                                            <option value="17">&#40;17&#41; Charente Maritime </option>
                                            <option value="18">&#40;18&#41; Cher </option>
                                            <option value="19">&#40;19&#41; Corrèze </option>
                                            <option value="21">&#40;21&#41; Côte d'Or </option>
                                            <option value="22">&#40;22&#41; Côtes d'Armor </option>
                                            <option value="23">&#40;23&#41; Creuse </option>
                                            <option value="24">&#40;24&#41; Dordogne </option>
                                            <option value="25">&#40;25&#41; Doubs </option>
                                            <option value="26">&#40;26&#41; Drôme </option>
                                            <option value="27">&#40;27&#41; Eure </option>
                                            <option value="28">&#40;28&#41; Eure et Loir </option>
                                            <option value="29">&#40;29&#41; Finistère </option>
                                            <option value="2B">&#40;2B&#41; Haute-Corse </option>
                                            <option value="30">&#40;30&#41; Gard </option>
                                            <option value="31">&#40;31&#41; Haute Garonne </option>
                                            <option value="53">&#40;53&#41; Mayenne </option>
                                            <option value="60">&#40;60&#41; Oise </option>
                                            <option value="61">&#40;61&#41; Orne </option>
                                            <option value="32">&#40;32&#41; Gers </option>
                                            <option value="33">&#40;33&#41; Gironde </option>
                                            <option value="34">&#40;34&#41; Hérault </option>
                                            <option value="35">&#40;35&#41; Ille et Vilaine </option>
                                            <option value="36">&#40;36&#41; Indre </option>
                                            <option value="37">&#40;37&#41; Indre et Loire </option>
                                            <option value="38">&#40;38&#41; Isère </option>
                                            <option value="39">&#40;39&#41; Jura </option>
                                            <option value="40">&#40;40&#41; Landes </option>
                                            <option value="42">&#40;42&#41; Loire </option>
                                            <option value="43">&#40;43&#41; Haute Loire </option>
                                            <option value="44">&#40;44&#41; Loire Atlantique </option>
                                            <option value="45">&#40;45&#41; Loiret </option>
                                            <option value="46">&#40;46&#41; Lot </option>
                                            <option value="47">&#40;47&#41; Lot et Garonne </option>
                                            <option value="63">&#40;63&#41; Puy de Dôme </option>
                                            <option value="80">&#40;80&#41; Somme </option>
                                            <option value="81">&#40;81&#41; Tarn </option>
                                            <option value="48">&#40;48&#41; Lozère </option>
                                            <option value="49">&#40;49&#41; Maine et Loire </option>
                                            <option value="50">&#40;50&#41; Manche </option>
                                            <option value="52">&#40;52&#41; Haute Marne </option>
                                            <option value="54">&#40;54&#41; Meurthe et Moselle </option>
                                            <option value="55">&#40;55&#41; Meuse </option>
                                            <option value="56">&#40;56&#41; Morbihan </option>
                                            <option value="57">&#40;57&#41; Moselle </option>
                                            <option value="58">&#40;58&#41; Nièvre </option>
                                            <option value="59">&#40;59&#41; Nord </option>
                                            <option value="62">&#40;62&#41; Pas de Calais </option>
                                            <option value="64">&#40;64&#41; Pyrénées Atlantiques </option>
                                            <option value="65">&#40;65&#41; Hautes Pyrénées </option>
                                            <option value="66">&#40;66&#41; Pyrénées Orientales </option>
                                            <option value="67">&#40;67&#41; Bas Rhin </option>
                                            <option value="68">&#40;68&#41; Haut Rhin </option>
                                            <option value="70">&#40;70&#41; Haute Saône </option>
                                            <option value="71">&#40;71&#41; Saône et Loire </option>
                                            <option value="69">&#40;69&#41; Rhône </option>
                                            <option value="72">&#40;72&#41; Sarthe </option>
                                            <option value="73">&#40;73&#41; Savoie </option>
                                            <option value="74">&#40;74&#41; Haute Savoie </option>
                                            <option value="75">&#40;75&#41; Paris </option>
                                            <option value="76">&#40;76&#41; Seine Maritime </option>
                                            <option value="77">&#40;77&#41; Seine et Marne </option>
                                            <option value="78">&#40;78&#41; Yvelines </option>
                                            <option value="79">&#40;79&#41; Deux Sèvres </option>
                                            <option value="82">&#40;82&#41; Tarn et Garonne </option>
                                            <option value="83">&#40;83&#41; Var </option>
                                            <option value="84">&#40;84&#41; Vaucluse </option>
                                            <option value="85">&#40;85&#41; Vendée </option>
                                            <option value="86">&#40;86&#41; Vienne </option>
                                            <option value="87">&#40;87&#41; Haute Vienne </option>
                                            <option value="88">&#40;88&#41; Vosges </option>
                                            <option value="973">&#40;973&#41; Guyane </option>
                                            <option value="976">&#40;976&#41; Mayotte </option>
                                            <option value="89">&#40;89&#41; Yonne </option>
                                            <option value="90">&#40;90&#41; Territoire de Belfort </option>
                                            <option value="91">&#40;91&#41; Essonne </option>
                                            <option value="92">&#40;92&#41; Hauts de Seine </option>
                                            <option value="93">&#40;93&#41; Seine Saint Denis </option>
                                            <option value="94">&#40;94&#41; Val de Marne </option>
                                            <option value="95">&#40;95&#41; Val d'Oise </option>
                                            <option value="971">&#40;971&#41; Guadeloupe </option>
                                            <option value="972">&#40;972&#41; Martinique </option>
                                            <option value="974">&#40;974&#41; Réunion </option>
                                            <option value="975">&#40;975&#41; Saint Pierre et Miquelon </option>
                                        </select>
                                        <label class='m-2' for='volvehicule'>Véhicule Volé :</label>
                                        <input type='text' class='form-control mx-3 text-center fw-bold' name='volvehicule' id='volvehicule' aria-describedby='volvehicule' placeholder='Véhicule volé' disabled>
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
