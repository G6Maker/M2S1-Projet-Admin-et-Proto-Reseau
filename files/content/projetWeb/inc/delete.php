<?php
require_once("based_config.php");
require_once('db.function.php');
function find($list,$id){
    foreach($list as $pr){
        if($pr->getId()==$id){
            return $pr;
        }
    }
    return null;
}
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
$db = DB::getInstance();
if(isset($_GET['Projetid'])){
    $projets = $db->getProjets();
    $pr = find($projets, $_GET['Projetid']);
    if($pr == null){
        exit();
    }
    //redirection si pas le propriétaire
    if(User::getLogUser()->getId()!=$pr->getUser_id()){
        Alert::setAlert(0, "Vous n'êtes pas le propriétaire du projet.<br>Action refusé");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    if($db->deleteProjet($pr)>0){
        echo "projet delete";
    } else {
        echo "projet NOT delete";
        Alert::setAlert(1, "Le projet n'a pas été supprimé.<br>Veillez réessayer");
    }
}
if(isset($_GET['Groupid'])){
    $groups = $db->getAllGroups();
    $pr = find($groups, $_GET['Groupid']);
    if($pr == null){
        exit();
    }
    //redirection pas autorisé
    $projets = $db->getProjets();
    $projet = find($projets, $pr->getProjet_id());
    if(User::getLogUser()->getId()!=$projet->getUser_id()){
        Alert::setAlert(0, "Vous n'êtes pas le propriétaire du projet.<br>Action refusé");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    if($db->deleteGroup($pr)>0){
        echo "Group delete";
    } else {
        echo "Group NOT delete";
        Alert::setAlert(1, "Le groupe n'a pas été supprimé.<br>Veillez réessayer");
    }
}
if(isset($_GET['Taskid'])){
    $tasks = $db->getAllTasks();
    $pr = find($tasks, $_GET['Taskid']);
    if($pr == null){
        exit();
    }
    //redirection pas autorisé
    $groups = $db->getAllGroups();
    $grp = find($groups, $pr->getgroup_id());
    $projets = $db->getProjets();
    $projet = find($projets, $grp->getProjet_id());
    if(User::getLogUser()->getId()!=$projet->getUser_id()){
        Alert::setAlert(0, "Vous n'êtes pas le propriétaire du projet.<br>Action refusé");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    if($db->deleteTask($pr)>0){
        echo "Task delete";
    } else {
        echo "Task NOT delete";
        Alert::setAlert(1, "La tâche n'a pas été supprimé.<br>Veillez réessayer");
    }
}
if(isset($_GET['Userid'])){
    $users = $db->getUsers();
    $pr = find($users, $_GET['Userid']);
    if($pr == null){
        exit();
    }
    //redirection 'casse toi de la petit con'
    if(User::getLogUser()->getId()!=$pr->getId()){
        Alert::setAlert(0, "Vous n'êtes pas l'utilisateur concerné'.<br>Action refusé");
        Html::redirection($_SERVER['HTTP_REFERER']);
        exit;
    }
    if($db->deleteUser($pr)>0){
        echo "User delete";
    } else {
        echo "User NOT delete";
        Alert::setAlert(1, "L'utilisateur n'a pas été supprimé.<br>Veillez réessayer");
    }
}

Html::redirection($_SERVER['HTTP_REFERER']);
?>