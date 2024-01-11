<?php
if(!defined('KANBAN_ROOT')){
    die("Vous ne pouvez pas accèder a ce fichier directement");
}

if(!defined('KANBAN_CONFIG')){
    define('KANBAN_CONFIG', KANBAN_ROOT . '/config');
}

?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-lg-7 mt-3">
            <h2 class="text-center">Information sur la base de données</h2>
            <form class="mt-5" action="install/install2.php" method="post">
                <div class="form-group row">
                    <label for="hostname" class="col-sm-3 col-form-label">Nom de l'hote</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="hostname" name="hostname" placeholder="localhost" required>
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="port" class="col-sm-3 col-form-label">Port</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="port" name="port" min="0" max="65535" value="1433" required>
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="username" class="col-sm-3 col-form-label">Nom d'utilisateur</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="password" class="col-sm-3 col-form-label">Mot de passe</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="form-group row mt-4">
                    <label for="db" class="col-sm-3 col-form-label">Base de données</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="db" name="db" placeholder="Nom de la base de données" required>
                    </div>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto mt-4">
                    <button class="btn btn-secondary" type="button" onclick="activate()" id="testDB">Tester la connexion</button>
                    <div id="div">

                    </div>
                    <button class="btn btn-primary" type="submit" id="valider" disabled>Validé</button>
                    <script>
                        function values_to_string(){
                            var listInput = document.getElementsByTagName('input');
                            var res = '';
                            for(var input of listInput){
                                res = res + input.id + '=' + input.value + '&';
                            }
                            return res;
                        }
                        function load(url, element, values)
                        {
                            fetch(url, {
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
                                if(html == 'success'){
                                    document.getElementById("testDB").className = "btn btn-success";
                                    document.getElementById("valider").disabled = false;
                                } else {
                                    document.getElementById("testDB").className = 'btn btn-danger'
                                    document.getElementById("valider").disabled = true;
                                }
                            }).catch(function(error) {      
                                document.getElementById("testDB").className = 'btn btn-danger'                  // catch
                                console.log('Request failed', error);
                            })
                        }
                        function activate(){
                            load("install/test.php", document.getElementById("div"), values_to_string());
                        }
                    </script>
                </div>
            </form>
        </div>
    </div>
</div>