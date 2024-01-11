<?php
session_start();
if(!defined('KANBAN_ROOT')){
    //dirname renvoie le chemin d'acces du parent au xeme level precedent
    //ici __FILE vaut [...]/projetWeb/inc/based_config.php
    //auquel on retire /inc/based_config.php d'ou le 2
    define('KANBAN_ROOT', dirname(dirname(__FILE__)));
}
define('KANBAN_CONFIG', KANBAN_ROOT . '/config');
