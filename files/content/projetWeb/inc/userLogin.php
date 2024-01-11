<?php
require_once(dirname(dirname(__FILE__)) . "/inc/based_config.php");
require_once(KANBAN_ROOT . '/inc/includes.php');
require_once(KANBAN_ROOT . '/front/doctype.php');

if(!isset($_POST['login'])||!isset($_POST['password'])){
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
//check login existe
$db = DB::getInstance();
$users = $db->getUsers();
foreach ($users as $user) {
    if(strcmp($user->getLogin(), $_POST['login'])==0&&password_verify($_POST['password'], $user->getPassword())) {
        if(User::storeLogUser($user)){
            echo $user->getLogin() . " vrai<br>";
            Html::redirection("/projetWeb/");
            exit;
        }
    }
}
Alert::setAlert(1, "Login ou mot de passe incorrect.<br>Veillez réessayer");
Html::redirection($_SERVER['HTTP_REFERER']);
?>