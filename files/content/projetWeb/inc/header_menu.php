<?php
require_once('user.php');
require_once('alert.php');
class header_menu{

public static function print_header($title){
?>
<title>
  <?php echo "Kanban - ".$title ?>
</title>

<nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <div class="container-fluid">
    <!-- Brand -->
    <span class="navbar-brand mb-0 h1">Kanban</span>

    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/projetWeb/">Kanban</a>
        </li>
        <!-- si l'utilisateur est connecté -->
        <?php if (User::isLog()) { ?>
        <li class="nav-item">
          <a class="nav-link" href="/projetWeb/front/projetOwn.php">Mes projets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/projetWeb/front/compte.php">Mon compte</a>
        </li>
        <a href="/projetWeb/inc/userLogout.php" class="btn btn-secondary px-3 me-2 text-decoration-none">
          Deconnexion
        </a>
        <?php } else { ?>
        <!-- a afficher si deconnecté -->
        <a href="/projetWeb/front/connection.php" class="btn btn-link px-3 me-2 text-decoration-none">
          Connexion
        </a>
        <a href="/projetWeb/front/Inscription.php" class="btn btn-primary me-3 text-decoration-none">
          Inscription
        </a>
        <?php } ?>
        <!-- afficher le nom d'utilisateur en lien cliquable -->
      </ul>
    </div>
  </div>
</nav> 
<?php
    Alert::printAlert();
?>

<script>
    var aList = document.getElementsByClassName("nav-link");
    for(var a = 0; a < aList["length"]; a++) {
        if(aList[a].href == location.href){
            aList[a].className = aList[a].className + " active";
        }
    }
</script>
<?php
}

}
?>