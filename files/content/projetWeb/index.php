<?php
define('KANBAN_ROOT', __DIR__);
require_once(KANBAN_ROOT . "/inc/based_config.php");
//Si l'installation n'est pas détecté alors
if(!file_exists(KANBAN_CONFIG . "/config_db.php")){
    require_once(KANBAN_ROOT . "/front/doctype.php");
    if(file_exists(KANBAN_ROOT . "/install/install.php")){
        include(KANBAN_ROOT . "/install/install.php");
    } else {
        //send UTF8 headers 
        header('Content-Type: text/html; charset=utf-8');
        //include header
        //include(KANBAN_ROOT . '/front/header.php');
        //write the error
        echo '<div class="container-fluid mb-4">';
        echo '<div class="row justify-content-center">';
        echo '<div class="col-xl-6 col-lg-7 col-md-9 col-sm-12">';
        echo '<h2>Kanban semble être mal installé</h2>';
        echo '<p class=mt-2 mb-n2 alert alert-warning">';
        echo 'Le fichier ' . KANBAN_CONFIG . '/config_db.php ne semble pas exister';
        echo '<br>';
        echo 'Restorer le dossier d\'installation pour corriger le problème';
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    die();
}
//Si l'installation est détecté

include(KANBAN_ROOT . '/inc/includes.php');

header('Content-Type: text/html; charset=utf-8');
include(KANBAN_ROOT . '/front/header.php');


include(KANBAN_ROOT . '/front/kanban.php');


include(KANBAN_ROOT . '/front/footer.php');

