<?php
require_once("header.php");

Html::setTitle("Kanban - Projet");

if(!User::isLog()){
    Alert::setAlert(1, "Vous n'êtes pas connecté");
    Html::redirection("connection.php");
}
?>

<div class="container">
    <form class="row mt-3 align-items-center bg-dark rounded text-light" action="projetOwn.php" method="GET"> <?php //barre de "recherche" ?>
        <div class="col-auto">
            <label for="inputSelection" class="col-form-label">Quoi afficher ?</label>
        </div>
        <div class="col">
            <select class="form-select" name="affichage" id="QueryType" onchange="updateSelect()" aria-label="Default select example">
                <option value="0" <?php if (isset($_GET["affichage"])) {
                    echo ($_GET["affichage"] == 0 ? "selected" : "");
                    }?>>Kanban géré</option>
                <option value="1" <?php if (isset($_GET["affichage"])) {
                    echo ($_GET["affichage"] == 1 ? "selected" : "");
                    }?>>Kanban invité</option>
                <option value="2" <?php if (isset($_GET["affichage"])) {
                    echo ($_GET["affichage"] == 2 ? "selected" : "");
                    }?>>Tâche affecté</option>
            </select>
        </div>
        <div class="col-auto">
            <label for="inputSelection" class="col-form-label">Trie :</label>
        </div>
        <div class="col">
            <select class="form-select" name="sort" id="sortSelect" aria-label="Default select example">
                <option value="1" <?php if(!isset($_GET['sort']) || $_GET['sort'] == 1){
                    echo "selected";
                } ?>>Ordre alphabétique</option>
                <?php 
                if(isset($_GET['affichage']) && $_GET['affichage']== 2 ){
                    if(isset($_GET['sort']) && $_GET['sort'] == 2){
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="2" '. $selected . ' >Date limite de réalisation</option>';
                }
                ?>
            </select>
            <script>
                function updateSelect(){
                    var x = document.getElementById('QueryType').value;
                    var sortSelect = document.getElementById('sortSelect');
                    if(x==2){
                        opt = document.createElement('option');
                        opt.value = "2";
                        opt.text = "Date limite de réalisation";
                        sortSelect.add(opt,undefined)
                    } else {
                        if(sortSelect.options.length > 1){
                            sortSelect.remove(sortSelect.options.length-1);
                        }
                    }
                }
            </script>
        </div>
        <div class="col">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sortorder" id="flexRadioDefault1" value="asc" <?php if (isset($_GET["sortorder"])) {
                    echo ($_GET["sortorder"]=="asc"?"checked":"");
                } else {
                    echo "checked";
                }?>>
                <label class="form-check-label" for="flexRadioDefault1">
                    Croissant
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sortorder" id="flexRadioDefault2" value="desc" <?php if (isset($_GET["sortorder"])) {
                    echo ($_GET["sortorder"]=="desc"?"checked":"");
                    }?>>
                <label class="form-check-label" for="flexRadioDefault2">
                    Decroissant
                </label>
            </div>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Afficher</button>
        </div>
    </form>
    <div class="row"> <?php //affichage des resultats ?>
        <?php 
        if (!isset($_GET["affichage"])||$_GET["affichage"]==0) {//afficher les Kanbans géré
            
            $projets = DB::getInstance()->getProjets();
            $user = User::getLogUser();
            $max = count($projets);
            for($i = 0;$i<$max; $i++){
                if ($projets[$i]->getUser_id() != $user->getId()){
                    unset($projets[$i]);
                }
            }
            //trie a appliquer
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                default:
                    usort($projets, 'Projet::cmpalpha');
                    if (isset($_GET["sortorder"]) && $_GET["sortorder"] == "desc") {
                            array_reverse($projets);
                    }
                }
            } else {
                usort($projets, 'Projet::cmpalpha');
            }
            foreach($projets as $pr){
                $pr->printProjet();
            }
        }
        if (isset($_GET["affichage"])&&$_GET["affichage"]==1) {//afficher les Kanbans invité
            
            $projets = DB::getInstance()->getProjets();
            $user = User::getLogUser();
            $max = count($projets);
            for($i = 0;$i<$max; $i++){
                $pr = $projets[$i];
                if (!Access::isInvited($user, $pr)){
                    unset($projets[$i]);
                }
            }
            //trie a appliquer
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                default:
                    usort($projets, 'Projet::cmpalpha');
                    if (isset($_GET["sortorder"]) && $_GET["sortorder"] == "desc") {
                            array_reverse($projets);
                    }
                }
            } else {
                usort($projets, 'Projet::cmpalpha');
            }
            
            foreach($projets as $pr){
                $pr->printProjet();
            }
        }
        if (isset($_GET["affichage"]) && $_GET["affichage"] == 2) {//afficher les tache attribué
            ?>
            <div class="col">
                <form class="row mt-1 mb-1 p-1 bg-dark rounded text-light" action="projetOwn.php" method="GET"> <?php //barre de "recherche" ?>
                    <?php foreach($_GET as $key => $value){
                        if($key!="projet"){
                            echo '<input type="hidden" name="'.$key.'" value="'. $value .'">';
                        }
                    }
                    ?>
                    <div class="col-auto">
                        <label for="inputSelection" class="col-form-label">Projet :</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="projet" onchange="updateSelect()" aria-label="Default select example">
                            <option>Tous</option>
                            <?php
                                $projets = DB::getInstance()->getProjets();
                                foreach($projets as $pr){
                                    if(Access::haveRight(User::getLogUser(), $pr)){
                                        echo '<option value="'. $pr->getId() .'" '. ($_GET['projet']==$pr->getId()?"selected":"") .'>'. $pr->getName() .'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Afficher</button>
                    </div>
                </form>
            </div>
            <?php
            if(isset($_GET["projet"])&&$_GET["projet"]!="Tous"){
                $db = DB::getInstance();
                $projets = $db->getProjets();
                foreach($projets as $proj){
                    if($proj->getId() == $_GET["projet"]){
                        $pr = $proj;
                        break;
                    }
                }
                if (!isset($pr)){
                    exit;
                }
                $groups = $db->getGroups($pr);
                foreach($groups as $gr){
                    if(!isset($tasks)){
                        $tasks = $db->getTasks($gr);
                    } else {
                       array_merge($tasks, $db->getTasks($gr)); 
                    }
                }
            } else {
                $tasks = DB::getInstance()->getAllTasks();
            }
            $user = User::getLogUser();
            $max = count($tasks);
            for($i = 0;$i<$max; $i++){
                $ta = $tasks[$i];
                if ($ta->getUser_id() != $user->getId()){
                    unset($tasks[$i]);
                }
            }
            //trie a appliquer
            if(isset($_GET['sort'])){
                switch ($_GET['sort']) {
                    case 2:
                        if (!isset($_GET["sortorder"])||$_GET["sortorder"]=="asc"){
                            usort($tasks, 'Task::cmpDate');
                        } else {
                            usort($tasks, 'Task::cmpDate2');
                        }
                        break;
                default:
                    if (!isset($_GET["sortorder"])||$_GET["sortorder"]=="asc"){
                        usort($tasks, 'Task::cmpalpha');
                    } else {
                        usort($tasks, 'Task::cmpalpha2');
                    }
                }
            } else {
                usort($tasks, 'Task::cmpalpha');
            }
            foreach($tasks as $ta){
                $ta->printTask(false);
            }
        }
        ?>
    </div>
</div>


<?php

?>