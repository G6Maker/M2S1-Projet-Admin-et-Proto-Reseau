<?php
require_once('based_config.php');
require_once('includes.php');

//check la session ici !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
}
if(!isset($_GET['titre'])||!isset($_GET['idgroup'])||!isset($_GET['idproj'])){
    //retourner a la page d'avant !
    Alert::setAlert(0, "Erreur.<br>Veillez réessayer");
    Html::redirection($_SERVER['HTTP_REFERER']);
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
$gr = new Group($_GET['idgroup'], $_GET['idproj'], closetags(strip_tags(nl2br($_GET['titre']), "<em><strong><u>")), date("Y-m-d"));

$db = DB::getInstance();

if($gr->getId()==""){
    if($db->insertGroup($gr)>0){
        echo "reussi";
    } else {
        echo "fail";
        Alert::setAlert(1, "Le groupe n'a pas été créé.<br>Veillez réessayer");
    }
} else {
    if($db->updategroup($gr)>0){
        echo "reussi";
    } else {
        echo "fail";
        Alert::setAlert(0, "Le groupe n'a pas été modifié.");
    }
}

?>
<script>
    location.href = "<?php echo $_SERVER['HTTP_REFERER']; ?>";
</script>
<?php
echo '<form action="'. $_SERVER['HTTP_REFERER'] .'">';
echo '<button type="submit" class="btn btn-danger btn-lg btn-block">Echec de la redirection automatique</button>';
echo '</form>';
?>