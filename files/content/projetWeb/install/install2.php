<?php
function redirection ($url){
    header("Location: " . $url);
    //si le header a déja été envoyer
    echo '<form action="' . $url .'">';
    echo '<button type="submit" class="btn btn-danger btn-lg btn-block">Echec de la redirection automatique</button>';
    echo '</form>';
    exit();
}
//Essaie de connexion
try {
    $conn = new mysqli($_POST["hostname"], $_POST["username"], $_POST["password"], $_POST["db"], $_POST["port"]);
} catch (Exception $e){ //la connexion à échoué 
    $error = $e->getMessage();
    echo $error;
    redirection('../index.php');
}
//la connexion à échoué 
if($conn->connect_error){
    redirection('../index.php');
}

//création du dossier et écriture du fichier
// le chemin du dossier à créer
$dir = "../config";
 
// Verifier l'existence du dossier
if(!file_exists($dir)){
    // Tentative de création du répertoire
    if(mkdir($dir)){
        echo "Répertoire créé avec succès.\n";
    } else {
        echo "ERREUR : Le répertoire n'a pas pu être créé. Verifier que le propriétaire est bien www-data";
        die();
    }
}

$filename = 'config_db.php';

$path = $dir . '/' . $filename;

$f = fopen($path, 'wb');
if (!$f) {
    die('Error creating the file ' . $path);
}
//ouverture php
fwrite($f, "<?php\n");
//definition des macros
fwrite($f, "define('DB_HOSTNAME','" . $_POST["hostname"] . "');\n");
fwrite($f, "define('DB_USERNAME','" . $_POST["username"] . "');\n");
fwrite($f, "define('DB_PASSWORD','" . $_POST["password"] . "');\n");
fwrite($f, "define('DB_DB','" . $_POST["db"] . "');\n");
fwrite($f, "define('DB_PORT','" . $_POST["port"] . "');\n");
//fermeture du php
fwrite($f, "?>\n");


//ecriture de la requête en string
$query = "CREATE TABLE IF NOT EXISTS kanban_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL,
    password VARCHAR(120) NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100)
    )";
//Envoie des requêtes de création des tables
if ($conn->query($query) === TRUE) {
    echo "Table kanban_users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    unlink($path);
    die();
}

//ecriture de la requête en string
$query = "CREATE TABLE IF NOT EXISTS kanban_projet (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    name varchar(100),
    date date,
    public BOOLEAN NOT NULL
    )";
//Envoie des requêtes de création des tables
if ($conn->query($query) === TRUE) {
    echo "Table kanban_projet created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    unlink($path);
    die();
}
//ecriture de la requête en string
$query = "CREATE TABLE IF NOT EXISTS kanban_projet_access (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projet_id INT(6) UNSIGNED NOT NULL,
    user_id INT(6) UNSIGNED NOT NULL,
    read_only BOOLEAN NOT NULL
    )";
//Envoie des requêtes de création des tables
if ($conn->query($query) === TRUE) {
    echo "Table kanban_projet_access created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    unlink($path);
    die();
}
//ecriture de la requête en string
$query = "CREATE TABLE IF NOT EXISTS kanban_group (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    projet_id INT(6) UNSIGNED NOT NULL,
    name varchar(100),
    date date
    )";
//Envoie des requêtes de création des tables
if ($conn->query($query) === TRUE) {
    echo "Table kanban_group created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    unlink($path);
    die();
}
//ecriture de la requête en string
$query = "CREATE TABLE IF NOT EXISTS kanban_task (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_id INT(6) UNSIGNED NOT NULL,
    titre varchar(100),
    description varchar(4000),
    user_id INT(6) UNSIGNED,
    date date
    )";
//Envoie des requêtes de création des tables
if ($conn->query($query) === TRUE) {
    echo "Table kanban_task created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    unlink($path);
    die();
}

//fermeture de la connexion
$conn->close();

//la redirection ici !
redirection('../index.php');
exit();
?>