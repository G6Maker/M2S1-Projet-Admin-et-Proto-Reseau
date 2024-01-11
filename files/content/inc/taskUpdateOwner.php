<?php
require_once('based_config.php');
require_once('includes.php');
//check la session ici
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

if(!isset($_GET['id'])){
    //retourner a la page d'avant !
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
$db = DB::getInstance();

$ta = $db->getTask(new Task($_GET['id']))[0];
if ($ta == null ){
    Alert::setAlert(1, "La tâche n'existe pas.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

$tk = new Task($ta->getId(),$ta->getgroup_id(), $ta->getTitre(), $ta->getDescription(), User::getLogUser()->getId(), $ta->getDate());

if($db->updateTask($tk)>0){
    echo "reussi";
} else {
    echo "fail";
    Alert::setAlert(0, "Le tâche n'a pas été modifié.");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

Html::redirection($_SERVER['HTTP_REFERER']."#Task".$_GET['id']);

?>