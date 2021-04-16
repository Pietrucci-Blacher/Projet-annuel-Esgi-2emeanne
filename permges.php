<?php
    require_once(__DIR__ . '/request/user.php');
    if(getUserStatus($_SESSION['id']) != "admin" || !isset($_SESSION)) {
        header('Location: index.php');
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
    <meta name="description" content="Page de gestion des permis de Ultimate Parcer">
    <meta name="keywords" content="livraison,colis">
    <script src="js/formpermges.js"></script>
    <title>Ultimate Parcel - Gestion des permis </title>
</head>
<?php require_once('include/header.php'); ?>
<body>
<br>
<div class="container my-4">
    <div class="row">
        <div class="col-9 mx-auto" style="background-color: white;">
            <h2 class="text-center">Gestion des permis</h2>
            <br>
            <div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Zone Géographique</th>
                            <th scope="col">Nombre de kilomètres</th>
                            <th scope="col">Permis Validé</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <?php
                        $delinfos = getalldeliverydata();
                        foreach ( $delinfos as $deluser) {?>
                            <tr>
                                <td><?php echo $deluser['client']; ?></td>
                                <td><?php echo $deluser['nom'] ?></td>
                                <td><?php echo $deluser['prenom'] ?></td>
                                <td><?php echo $deluser['zoneGeo']; ?></td>
                                <td><?php echo $deluser['nbKm']; ?></td>
                                <td><?php if($deluser['validatedperm'] == true){
                                        echo "<span class='fw-bold text-center' style='color: greenyellow'>Oui</span>";
                                    }else{
                                        echo "<span class='fw-bold text-center' style='color: red'>Non</span>";
                                    }?></td>
                                <td>
                                    <button user-id="<?php echo $deluser['client']?>" type="button" title="Afficher le permis" class="btn btn-info showpermis"><span style="color: white"><i  class="far fa-address-card"></i></span></button>
                                </td>
                            </tr>
                        <?php } ;  ?>
                    </table>
                </div>
            </div>
            <br>
            <div class='container-fluid mb-3 showperm'>
                <div class='row'>
                    <div class='col'>
                        <h4 class='text-center'>Lien vers le permis</h4>
                        <div class="text-center">
                            <a id='drivinglicence' target='_blank'>Visualiser le nombre de points sur le permis</a>
                        </div>
                        <hr>
                        <h4 class='text-center'>Lien vers le nombre de points</h4>
                        <div class="text-center">
                            <a id='pointslicence' target="_blank">Visualiser le nombre de points sur le permis</a>
                        </div>
                    </div>
                    <div class='col'>
                        <form class='form-check form-switch' method='post' enctype='multipart/form-data'>
                            <input type='checkbox' class='form-check-input' id='permval' name='permval'>
                            <label for='permval'>Valider le permis</label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

