<?php
require_once("based_config.php");
require_once('db.function.php');
//verifier l'identité!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if(!isset($_POST['id'])){
    die("la");
}

$db = DB::getInstance();
$tasks = $db->getAllTasks();
foreach($tasks as $ta){
    if($ta->getId()==$_POST['id']){
        echo $ta->printEditableTask(false);
    }
}
?>