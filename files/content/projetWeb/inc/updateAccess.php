<?php
require_once("based_config.php");
require_once("includes.php");
if(!User::isLog()){
    exit;
}

if(!isset($_POST['projet_id'])||!isset($_POST['user_id'])){
    exit;
}
$db = DB::getInstance();
foreach($db->getProjets() as $pr ){
    if ($pr->getId() == $_POST['projet_id']) {
        if($pr->getUser_id() == User::getLogUser()->getId()){
            foreach($db->getUserAccess($pr) as $access){
                if ($access->getUser_id() == $_POST['user_id']) {
                    $db->deleteAccess($access);
                    exit;
                }
            }
            $acc = new Access("", $pr->getId(), $_POST['user_id'], 0);
            $db->insertAccess($acc);
            echo "active";
            exit;
        }
    }
}
//dropdown-item active