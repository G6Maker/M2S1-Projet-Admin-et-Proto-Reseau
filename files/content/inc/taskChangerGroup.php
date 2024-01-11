<?php
require_once('based_config.php');
require_once('includes.php');

//check la session ici
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
    Html::redirection("/projetWeb/front/connection.php");
    exit;
}

if(!isset($_GET['taskid'])||!isset($_GET['left'])){
    //retourner a la page d'avant !
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

$ta = DB::getInstance()->getTask(new Task($_GET['taskid']))[0];

if(!$ta->haveRight()){
    Alert::setAlert(0, "Action non autorisé.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

if($_GET['left']==1){ // deplacer à droite
    $gr = $ta->getLeftGroup();
    if($gr!=null){
        $nta = new Task($ta->getId(), $gr->getId(), $ta->getTitre(), $ta->getDescription(), $ta->getUser_id(), $ta->getDate());
        if(DB::getInstance()->updateTask($nta)>0){
            echo "reussi";
        } else {
            echo "fail";
            Alert::setAlert(1, "Le tache n'a pas été déplacé.<br>Veillez réessayer");
        }
    }
} else if($_GET['left']==0){ //deplacer à gauche
    $gr = $ta->getRightGroup();
    if($gr!=null){
        $nta = new Task($ta->getId(), $gr->getId(), $ta->getTitre(), $ta->getDescription(), $ta->getUser_id(), $ta->getDate());
        if(DB::getInstance()->updateTask($nta)>0){
            echo "reussi";
        } else {
            echo "fail";
            Alert::setAlert(1, "Le tache n'a pas été déplacé.<br>Veillez réessayer");
        }
    }
} else {
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
Html::redirection(($_SERVER['HTTP_REFERER']."#Task".$_GET['taskid']));
exit;
?>