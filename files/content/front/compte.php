<?php
require_once(dirname(dirname(__FILE__)) . "/inc/based_config.php");
require_once(KANBAN_ROOT . '/inc/includes.php');
require_once(KANBAN_ROOT . '/front/doctype.php');
header_menu::print_header("Mon Compte");
if(!User::isLog()){
        Alert::setAlert(1, "Vous n'êtes pas connecté");
        Html::redirection("connection.php");
}
?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <form action="/projetWeb/inc/userUpdate.php" method="POST">
                <div class="form-group">
                    <label for="exampleInputNom">Nom</label>
                    <input type="text" class="form-control" id="exampleInputNom" name="nom" placeholder="Entrez votre nom" value="<?php echo User::getLogUser()->getLastname();?>" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPrenom1">Prenom</label>
                    <input type="text" class="form-control" id="exampleInputPrenom1" name="prenom" placeholder="Entrez votre prenom" value="<?php echo User::getLogUser()->getFirstname();?>" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputLogin1">Login</label>
                    <input type="text" class="form-control" id="exampleInputLogin1" name="login" placeholder="Entrez votre nom d'utilisateur" value="<?php echo User::getLogUser()->getLogin();?>" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Entrez un mot de passe">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>