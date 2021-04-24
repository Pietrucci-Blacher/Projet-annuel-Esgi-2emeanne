<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
  <a class="navbar-brand ms-5" href="index.php"><img src="asset/largeLogo.png" width="250" height="50" alt=""></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarSupportedContent">
      <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
        <li class="nav-item">
          <a class="nav-link" aria-current="true" href="index.php">Accueil</a>
        </li>
        <?php if(!isset($_SESSION['name'])){ ?>
        <li class="nav-item">
          <a class="nav-link" href="parcelTracking.php">Suivre mon colis</a>
        </li>
        <?php }  ?>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tarifs
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php if(isset($_SESSION['rank']) && $_SESSION['rank'] != "entreprise"){ ?>
            <li><a class="dropdown-item" href="priceListDeliverer.php">Livreurs</a></li>
          <?php } elseif(isset($_SESSION['rank']) && $_SESSION['rank'] != "livreur"){ ?>
            <li><a class="dropdown-item" href="priceListParcel.php">Entreprises</a></li>
          <?php }else{ ?>
              <li><a class="dropdown-item" href="priceListDeliverer.php">Livreurs</a></li>
              <li><a class="dropdown-item" href="priceListParcel.php">Entreprises</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php if(isset($_SESSION['name']) && $_SESSION['rank'] == "admin"){?>
          <li class="nav-item"><a class="nav-link" href="parcelToDeposit.php">Attribution des colis</a></li>
            <li class="nav-item"><a class="nav-link" href="gestion.php">Gestion du site</a></li>
            <li class="nav-item"><a class="nav-link" href="permges.php">Gestion des permis</a></li>
            <li class="nav-item"><a class="nav-link" href="priceParcelAdmin.php">Gérer les tarifs des colis</a></li>
        <?php } if(isset($_SESSION['name']) && $_SESSION['rank'] == "livreur"){ ?>
            <li class="nav-item"><a class="nav-link" href="#">Mes livraisons</a></li>
        <?php } if(isset($_SESSION['name']) && $_SESSION['rank'] == "entreprise"){ ?>
              <li class="nav-item"><a class="nav-link" href="searchParcelCompany.php">Gérer les colis</a></li>
              <li class="nav-item"><a class="nav-link" href="paiementParcel.php">Payer les colis</a></li>
              <li class="nav-item"><a class="nav-link" href="billHistoric.php">Historique des factures</a></li>
        <?php } if(isset($_SESSION['name']) &&  isset($_SESSION['id'])){ ?>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $_SESSION['name'] ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="profile.php">Mon profil</a></li>
              <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
           </ul>
        <?php } ?>
          </li>
        <?php if(!isset($_SESSION['name'])){ ?>
            <li class="nav-item"><a class="nav-link" href="connect.php">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
        <?php } ?>
         <li class="nav-item dropdown" id="translate">
         <a class="nav-link dropdown-toggle french" id="navbarDropdownlang" role="button" data-bs-toggle="dropdown" aria-expanded="false" value="french"><i class="flag flag-fr"></i> <span>Français</span></a>
         <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><button class="dropdown-item english" id="itemsecond" value="english"><i class="flag flag-gb"></i> <span>English</span></button></li>
          <li><button class="dropdown-item spanish" id="itemthird" value="spanish"><i class="flag flag-es"></i> <span>Español</span></button></li>
         </ul>
        </li>
    </div>
  </div>
</nav>
