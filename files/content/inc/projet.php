<?php
require_once("based_config.php");
require_once("includes.php");
class Projet
{
	private $id;
	private $user_id;
	private $name;
    private $date;
    private $public;

	/**
	 * @param $id
	 * @param $projet_id
	 * @param $name
     * @param $date
     * @param $public
	 */
	public function __construct($id="", $user_id="", $name="", $date="", $public="")
	{
		$this->id = $id;
		$this->user_id = $user_id;
		$this->name = $name;
        $this->date = $date;
        $this->public = $public;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getUser_id()
	{
		return $this->user_id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}
    
    /**
	 * @return mixed
	 */
    public function getDate()
	{
		return $this->date;
	}
    public function getPublic(){
        return $this->public;
    }

    public function printProjet(){
		echo '<div class="container-fluid">';
		echo '<div class="row py-3" id=Projet'. $this->getId().'>';
		echo '<div class="col">';
		echo '</div>';
		echo '<div class="col align-self-center">';
		echo '<h4 class="text-center">'. $this->getName() .'</h4>';
		echo '</div>';
		echo '<div class="col align-self-end">';
		if (User::isLog()&& $this->getUser_id() == User::getLogUser()->getId()) {
			$this->printActionDropdown(get_class($this));
		}
		echo '</div>';
		echo '</div>';
		
        echo '<div class="row flex-row flex-sm-nowrap py-3 overflow-auto" id='. $this->getId() .'>';
		$db = DB::getInstance();
		$groups = $db->getGroups($this);
		foreach($groups as $gr){
			$gr->printGroup();
		}
		//check if connecté
		if (User::isLog() && $this->getUser_id() == User::getLogUser()->getId()) {
			$this->printAddGroupButton();
		}
        echo '</div>';
		echo '</div>';
    }

	private function printActionDropdown($type){
		?>
		<div class="dropdown d-flex flex-row-reverse">
			<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
				<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
				<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
			</svg>
			</button>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
				<li>
					<a class="dropdown-item" onclick="changeToEdit<?php echo $type; ?>(<?php echo $this->getId() ?>)" >Modifier 
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
							<path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
						</svg>
					</a>
				</li>
				<li>
					<a class="dropdown-item" href="/projetWeb/inc/delete.php?<?php echo $type; ?>id=<?php echo $this->getId(); ?>">Supprimer
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
							<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
							<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
						</svg>
					</a>
				</li>
			</ul>
		</div>
		<script>
			function changeToEdit<?php echo $type; ?>(id){
				var proj = document.getElementById("<?php echo $type; ?>"+id);
				//ajax pour demander un projet editable.
				var url = "/projetWeb/inc/edit<?php echo $type; ?>.php";
				fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'Content-Length': ("id="+id).length
                            },
                            body: ("id="+id)
                        }).then(function (response) {
                            // The API call was successful!
                            return response.text();
                        }).then(function (html) {
                            // This is the HTML from our response as a text string
                            proj.innerHTML = html;
                        }).catch(function(error) {              // catch
                            console.log('Request failed', error);
                        })
			}
		</script>
		<?php
	}

	private function printAddGroupButton(){
		//ajouter un groupe au projet.
		?>
		<div class="col-sm-6 col-md-4 col-xl-3" id="colbut<?php echo $this->getId() ?>">
			<button type="button" onclick="addNewGroup(<?php echo $this->getId() ?>)" class="btn btn-outline-dark btn-lg">Nouveau Groupe</button>
		</div>
		<?php
	}
	public function printEditableProjet($isNew = false){
		if(!$this->getPublic()){
			$prive = "selected";
			$public = "";
		} else {
			$public = "selected";
			$prive = "";
		}
		echo '<div class="container-fluid">';
		echo '<form action="/projetWeb/inc/projetUpdate.php" method="get">';
		echo '<div class="input-group">';
		echo '<input class="text-uppercase text-truncate form-control" type="text" id="titre'. $this->getId() .'" name="titre" placeholder="Titre du Projet" value="'. $this->getName() .'">';
		echo '<select class="custom-select" name="public" id="public'. $this->getId() .'">
					<option '. $prive .' value="0">Privé</option>
					<option '. $public .' value="1">Public</option>
				</select>';
				if($isNew == false){
					$db = DB::getInstance();
					$users = $db->getUsers();
					?>
					<div class="btn-group">
					<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
						Utilisateur autorisé
					</button>
					<ul class="dropdown-menu">
						<?php
						foreach($users as $user){
							if($user->getId() != User::getLogUser()->getId()){
								if(Access::haveRight($user, $this)){
									echo '<li><a class="dropdown-item active" id="'. $this->getId() . 'u'. $user->getId() .'" onclick="updateAccess('. $this->getId() . ','. $user->getId() .')">'.$user->getLastname() . ' ' . $user->getFirstname().'</a></li>';
								} else {
									echo '<li><a class="dropdown-item" id="'. $this->getId() . 'u'. $user->getId() .'" onclick="updateAccess('. $this->getId() . ','. $user->getId() .')">'.$user->getLastname() . ' ' . $user->getFirstname().'</a></li>';
								}  
							}
						}
						?>
					</ul>
					</div>
				<?php
				}
		echo '<input class="btn btn-outline-secondary" type="submit">';
		echo '</div>';
		echo '<input type="hidden" name="idproj" value="'. $this->getId() .'">';
		echo '</form>';
		echo '</div>';
		echo '<div class="row flex-row flex-sm-nowrap py-3 overflow-auto">';
		echo '</div>';
    }

	/**
	 * Summary of cmpalpha
	 * Compare name of $p1 and $p2
	 * with strcmp
	 * @param Projet $p1
	 * @param Projet $p2
	 * @return int
	 */
	public static function cmpalpha($p1, $p2){
		return strcmp($p1->getName(), $p2->getName());
	}
	/**
	 * Summary of cmpalpha
	 * Compare name of $p1 and $p2
	 * with strcmp but inverted
	 * @param Projet $p1
	 * @param Projet $p2
	 * @return int
	 */
	public static function cmpalpha2($p1, $p2){
		return strcmp($p2->getName(), $p1->getName());
	}
}
?>