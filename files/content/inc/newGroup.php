<?php
require_once('group.php');
if(isset($_POST['id'])){
    $gr = new Group("", $_POST['id'], "", "");
    $gr->printEditableGroup();
}
?>