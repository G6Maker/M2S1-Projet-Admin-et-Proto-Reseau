<?php
require_once(dirname(dirname(__FILE__)) . "/inc/based_config.php");
require_once(KANBAN_ROOT . '/inc/includes.php');
require_once(KANBAN_ROOT . '/front/doctype.php');
header_menu::print_header("Connexion");
if(User::isLog()){
    Alert::setAlert(1, "Vous êtes connecté");
    Html::redirection("/projetWeb/index.php");
}
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <form action="/projetWeb/inc/userLogin.php" method="POST">
                <div class="form-group">
                    <label for="exampleInputLogin1">Login</label>
                    <input type="text" class="form-control" id="exampleInputLogin1" name="login" placeholder="Entrez votre nom d'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Entrez un mot de passe" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>