<?php
require_once('task.php');
if(isset($_POST['id'])){
    $ta = new Task("", $_POST['id'], "", "");
    $ta->printEditableTask();
}
?>