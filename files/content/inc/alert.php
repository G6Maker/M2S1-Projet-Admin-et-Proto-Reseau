<?php
require_once("based_config.php");
require_once("includes.php");
class Alert{
    private static function isAlertDanger(){
        //demarrer la session si pas deja fait
        return isset($_SESSION['alertDanger']) && $_SESSION['alertDanger'] != null && $_SESSION['alertDanger'] != "";
    }
    private static function isAlertWarning(){
        //demarrer la session si pas deja fait
        return isset($_SESSION['alertWarning']) && $_SESSION['alertWarning'] != null && $_SESSION['alertWarning'] != "";
    }
    private static function isAlertSuccess(){
        //demarrer la session si pas deja fait
        return isset($_SESSION['alertSuccess']) && $_SESSION['alertSuccess'] != null && $_SESSION['alertSuccess'] != "";
    }
    public static function printAlert(){
        //demarrer la session si pas deja fait
        if (self::isAlertDanger()) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $_SESSION['alertDanger'];
            echo '</div>';
            $_SESSION['alertDanger'] = "";
        }

        if (self::isAlertWarning()) {
            echo '<div class="alert alert-warning" role="alert">';
            echo $_SESSION['alertWarning'];
            echo '</div>';
            $_SESSION['alertWarning'] = "";
        }
        if (self::isAlertSuccess()) {
            echo '<div class="alert alert-success" role="alert">';
            echo $_SESSION['alertSuccess'];
            echo '</div>';
            $_SESSION['alertSuccess'] = "";
        }
    }
    /**
     * Summary of setAlert
     * level = 0 : alertWarning
     * level = -1 : alertSuccess
     * level else : alertDanger
     * @param mixed $level
     * @param mixed $message
     * @return void
     */
    public static function setAlert($level = 0, $message = ""){
        //demarrer la session si pas deja fait!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        if($level == 0){
            if ((isset($_SESSION['alertWarning']) && $_SESSION['alertWarning'] == "") || !isset($_SESSION['alertWarning'])){
                $_SESSION['alertWarning'] = $message;
            } else {
                $_SESSION['alertWarning'] = $_SESSION['alertWarning'] . "<br><br>" . $message;
            }
        } else if ($level == -1){
            if ((isset($_SESSION['alertSuccess']) && $_SESSION['alertSuccess'] == "") || !isset($_SESSION['alertSuccess'])){
                $_SESSION['alertSuccess'] = $message;
            } else {
                $_SESSION['alertSuccess'] = $_SESSION['alertSuccess'] . "<br><br>" . $message;
            }
        } else {
            if ((isset($_SESSION['alertDanger']) && $_SESSION['alertDanger'] == "") || !isset($_SESSION['alertDanger'])){
                $_SESSION['alertDanger'] = $message;
            } else {
                $_SESSION['alertDanger'] = $_SESSION['alertDanger'] . "<br><br>" . $message;
            }
        }
    }
}
?>