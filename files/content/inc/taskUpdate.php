<?php
require_once('based_config.php');
require_once('includes.php');
//check la session ici
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
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

if(!isset($_GET['titre'])||!isset($_GET['desc'])||!isset($_GET['idTask'])||!isset($_GET['idGroup'])||!isset($_GET['date-r'])||(isset($_GET['isdate'])&&$_GET['date-r']=="")||!isset($_GET['user_id'])){
    //retourner a la page d'avant !
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

$ta = DB::getInstance()->getTask(new Task($_GET['idTask']))[0];

if($ta!=null && !$ta->haveRight()){
    Alert::setAlert(0, "Action non autorisé.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
    exit;
}

if(isset($_GET['isdate'])){
    $date = $_GET['date-r'];
} else {
    $date = null;
}

if($_GET['user_id']!=-1){
    $userid = $_GET['user_id'];
} else {
    $userid = null;
}

$ta = new Task($_GET['idTask'], $_GET['idGroup'], closetags(strip_tags(nl2br($_GET['titre']), "<em><strong><u>")), closetags(nl2br(strip_tags($_GET['desc'], "<em><strong><u><br>"))), $userid, $date);

$db = DB::getInstance();

if($ta->getId()==""){
    if($db->insertTask($ta)>0){
        echo "reussi";
    } else {
        echo "fail";
        Alert::setAlert(1, "Le tâche n'a pas été créé.<br>Veillez réessayer");
    }
} else {
    if($db->updateTask($ta)>0){
        echo "reussi";
    } else {
        echo "fail";
        Alert::setAlert(0, "Le tâche n'a pas été modifié.");
    }
}

Html::redirection($_SERVER['HTTP_REFERER']."#Task".$_GET['idTask']);

?>