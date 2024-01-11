<?php
require_once('projet.php');
//ajouter le user id au proj, visibilité etc
$pr = new Projet("", "", "", "", "");
$pr->printEditableProjet(true);
?>