<?php
require_once("based_config.php");
require_once("includes.php");

if(User::isLog()){ //si l'utilisateur est connecté
    if(!isset($_POST['login'])||!isset($_POST['password'])||!isset($_POST['nom'])||!isset($_POST['prenom'])){
        Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    $us = User::getLogUser();
    $db = DB::getInstance();
    //check login existe
    if($us->getLogin()!=$_POST['login']){
        $users = $db->getUsers();
        foreach($users as $user){
            if($user->getLogin() == $_POST['login']){
                //retourner  a la page d'avant 
                Alert::setAlert(1, "Le login choisi existe déjà.<br>Veillez choisir un autre login.");
                Html::redirection($_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
    if(strlen($_POST['password'])<=6 && $_POST['password']!=""){
        Alert::setAlert(1, "Le mot de passe choisi est trop court.<br>Veillez choisir un autre mot de passe.");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    } else if ($_POST['password']==""){
        $password = $us->getPassword();
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    //le hash du mot de passe fait 60 de longueur.
    $us2 = new User($us->getId(), $_POST['login'], $password, $_POST['nom'], $_POST['prenom']);
    if($db->updateUser($us2)>0){
        echo "reussi";
        Alert::setAlert(-1, "L'utilisateur a été modifié.");
        User::storeLogUser($us2);
    } else {
        echo "fail";
        Alert::setAlert(1, "L'utilisateur n'a pas été modifié.");
    }
    //redirection de creation de compte
    Html::redirection($_SERVER['HTTP_REFERER']);
} else { // si l'utilisateur est pas connecté
    if(!isset($_POST['login'])||!isset($_POST['password'])||!isset($_POST['nom'])||!isset($_POST['prenom'])){
        Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    //check login existe
    $db = DB::getInstance();
    $users = $db->getUsers();
    foreach($users as $user){
        if($user->getLogin() == $_POST['login']){
            //retourner  a la page d'avant 
            Alert::setAlert(1, "Le login choisi existe déjà.<br>Veillez choisir un autre login.");
            Html::redirection($_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    if(strlen($_POST['password'])<=6){
        Alert::setAlert(1, "Le mot de passe choisi est trop court.<br>Veillez choisir un autre mot de passe.");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    //le hash du mot de passe fait 60 de longueur.
    $us = new User("", $_POST['login'], password_hash($_POST['password'],PASSWORD_DEFAULT), $_POST['nom'], $_POST['prenom']);
    if($db->insertUser($us)>0){
        echo "reussi";
        Alert::setAlert(-1, "L'utilisateur a été créé.");
    } else {
        echo "fail";
        Alert::setAlert(1, "L'utilisateur n'a pas été créé.<br>Veillez réessayer");
    }
    //redirection de creation de compte
    Html::redirection("/projetWeb/front/connection.php");
}
Html::redirection("/projetWeb/index.php");
?>