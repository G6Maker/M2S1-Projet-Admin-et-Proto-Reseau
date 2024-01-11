<?php
require_once("based_config.php");
require_once('db.function.php');
//verifier l'identité!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if(!isset($_POST['id'])){
    die("la");
}

$db = DB::getInstance();
$projets = $db->getProjets();
foreach($projets as $pr){
    if($pr->getId()==$_POST['id']){
        echo $pr->printEditableProjet();
    }
}
?>