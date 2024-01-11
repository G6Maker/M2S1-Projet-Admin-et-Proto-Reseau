<?php
define('KANBAN_ROOT', dirname(__FILE__, 2));
require_once(KANBAN_ROOT . "/inc/based_config.php");
require_once('includes.php');
require_once(KANBAN_ROOT . "/front/doctype.php");


$db = DB::getInstance();
$users = $db->getUsers();
$projet = new Projet(24, 17);

?>
<div class="btn-group">
  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
    Utilisateur autorisé
  </button>
  <ul class="dropdown-menu">
    <?php
    foreach($users as $user){
        if($user->getId() != User::getLogUser()->getId()){
            if(Access::haveRight($user, $projet)){
                echo '<li><a class="dropdown-item active" id="'. $projet->getId() . 'u'. $user->getId() .'" onclick="updateAccess('. $projet->getId() . ','. $user->getId() .')">'.$user->getLastname() . ' ' . $user->getFirstname().'</a></li>';
            } else {
                echo '<li><a class="dropdown-item" id="'. $projet->getId() . 'u'. $user->getId() .'" onclick="updateAccess('. $projet->getId() . ','. $user->getId() .')">'.$user->getLastname() . ' ' . $user->getFirstname().'</a></li>';
            }  
        }
    }
    ?>
  </ul>
</div>
<script>
    function updateAccess(proj, user, existe){
        var values = "projet_id="+proj+"&user_id="+user;
        fetch("/projetWeb/inc/updateAccess.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Content-Length': values.length
            },
            body: values
        }).then(function (response) {
            // The API call was successful!
            return response.text();
        }).then(function (html) {
            // This is the HTML from our response as a text string
            document.getElementById(proj+"u"+user).className = "dropdown-item " + html;
        }).catch(function(error) {      
            //catch
            console.log('Request failed', error);
        })
    }
</script>