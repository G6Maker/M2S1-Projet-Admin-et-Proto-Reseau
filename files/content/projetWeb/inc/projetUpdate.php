<?php
require_once('based_config.php');
require_once('includes.php');
//check la session ici
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
if(!isset($_GET['titre'])||!isset($_GET['idproj'])||!isset($_GET['public'])){
    //retourner a la page d'avant !
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

function closetags ( $html ){
    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
    $openedtags = $result[1];

    preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
    $closedtags = $result[1];
    $len_opened = count ( $openedtags );

    if( count ( $closedtags ) == $len_opened ){
        return $html;
    }

    $openedtags = array_reverse ( $openedtags );

    for( $i = 0; $i < $len_opened; $i++ ){
        if ( !in_array ( $openedtags[$i], $closedtags ) ){
            $html .= "</" . $openedtags[$i] . ">";
        } else {
            unset ($closedtags[array_search ( $openedtags[$i], $closedtags)] );
        }
    }
    return $html;
}
$pr = new Projet($_GET['idproj']);
$pr =DB::getInstance()->getProjet($pr)[0];
if($pr!=null && $pr->getUser_id()!=User::getLogUser()->getId()){
    Alert::setAlert(0, "Erreur. Action non autorisé<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}
//ajouter le numero de l'utilisateur, variable de session !!!!!!!!!!!!!!!!!!!
$pr = new Projet($_GET['idproj'], User::getLogUser()->getId(), closetags(strip_tags(nl2br($_GET['titre']), "<em><strong><u>")), date("Y-m-d"), $_GET['public']);

$db = DB::getInstance();
if($pr->getId()==""){
    if($db->insertProjet($pr)>0){
        echo "reussi";
    } else {
        echo "fail";
        Alert::setAlert(1, "Le projet n'a pas été créé.<br>Veillez réessayer");
    }
} else {
    if($db->updateProjet($pr)>0){
        echo "reussi";
    } else {
        echo "fail";
        //Alert::setAlert(0, "Le projet n'a pas été modifié.");
    }
}

Html::redirection($_SERVER['HTTP_REFERER']);
?>