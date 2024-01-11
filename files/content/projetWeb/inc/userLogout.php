<?php
require_once(dirname(dirname(__FILE__)) . "/inc/based_config.php");
require_once(KANBAN_ROOT . '/inc/includes.php');
require_once(KANBAN_ROOT . '/front/doctype.php');

session_destroy();
Html::redirection("/projetWeb/");