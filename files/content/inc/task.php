<?php
require_once("based_config.php");
require_once("includes.php");
class Task
{
	private $id;
	private $group_id;
	private $titre;
	private $description;
    private $user_id;
    private $date;

	/**
	 * @param $id
	 * @param $group_id
	 * @param $titre
	 * @param $description
	 * @param $user_id
     * @param $date
	 */
	public function __construct($id="", $group_id="", $titre="", $description="", $user_id="", $date="")
	{
		$this->id = $id;
		$this->group_id = $group_id;
		$this->titre = $titre;
		$this->description = $description;
		$this->user_id = $user_id;
        $this->date = $date;
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
	public function getgroup_id()
	{
		return $this->group_id;
	}

	/**
	 * @return mixed
	 */
	public function getTitre()
	{
		return $this->titre;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
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
    public function getDate()
	{
		return $this->date;
	}

	public function printTask($projetShow = true){
		if ($this->getProjet() == null || $this->getGroup() == null){
			return;
		}
		if ($this->getDate() != ""){
			$retard = ($this->getDate() < date("Y-m-d") ? "border-danger" : "");
		} else {
			$retard = "";
		}
		echo '<div class="card draggable shadow-sm ' . $retard . '" id="Task'.$this->getId().'">';
		if($projetShow==false){
			echo "<h3>Projet: ". $this->getProjet()->getName() ."</h3>";
			echo '<h4>Groupe: '. $this->getGroup()->getName() .'</h4>';
		}
		echo '<div class="card-body p-2">';
		//titre
		echo '<div class="row py-3">';
		echo '<div class="col">';
		echo '<div class="card-title">';
		echo $this->getTitre();
		echo '</div>';
		echo '</div>';
		echo '<div class="col">';
		if (User::isLog() && $this->haveUpdateRight()) {
			$this->printActionDropdown(get_class($this));
		}
		echo '</div>';
		echo '</div>';
		//description
		echo '<p>' . $this->getDescription() . '</p>';
		//afficher la date et l'attribution
		if($this->getDate()!=null || $this->getUser_id()!=null){
			echo '<p>A faire';
			if ($this->getDate() != null){
				echo ' avant le '. $this->getDate();
			}
			if ($this->getUser_id()!=""){
				$user = DB::getInstance()->getUser(new User($this->getUser_id()))[0];
				if ($user!=null){
					echo ' par ' . $user->getFirstname() . ' ' . $user->getLastname();
				}
			}
			echo '</p>';
		}
		if($this->getUser_id()==null && Access::haveRight(User::getLogUser(), $this->getProjet())){
			echo '<a class="btn btn-outline-secondary" href="/projetWeb/inc/taskUpdateOwner.php?id='. $this->getId() .'">M\'attribuer la tache</a>';
		}
		if(User::isLog()&&($this->getProjet()->getUser_id()==User::getLogUser()->getId()||$this->getUser_id()==User::getLogUser()->getId())&&$projetShow==true){
			$left = $this->getLeftGroup();
			$right = $this->getRightGroup();
			if($left != null || $right != null){
				echo '<div class="row mt-1">';
				echo '<div class="col text-start">';
				if($left != null){
					echo '<a href="/projetWeb/inc/taskChangerGroup.php?taskid='. $this->getID() .'&left=1" class="btn btn-outline-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
					<path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
				  </svg></a>';
				}
				echo '</div>';
				echo '<div class="col text-end">';
				if($right != null){
					echo '<a href="/projetWeb/inc/taskChangerGroup.php?taskid='. $this->getID() .'&left=0" class="btn btn-outline-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
					<path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
				  </svg></a>';
				}
				echo '</div>';
				echo '</div>';
			}
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
	public function printEditableTask(){
		echo '<div class="card draggable shadow-sm">';
		echo '<div class="card-body p-2">';
		echo '<form action="/projetWeb/inc/taskUpdate.php" method="get">';
		echo '<input class="text-truncate form-control" type="text" id="titre" name="titre" placeholder="Titre de la tâche" value="'. $this->getTitre() .'">';
		echo '<textarea class="form-control" id="desc" name="desc" placeholder="Description de la tâche" rows="3">'. self::br2nl($this->getDescription()) .'</textarea>';
		echo '<input type="hidden" name="idTask" value="'. $this->getId() .'">';
		echo '<input type="hidden" name="idGroup" value="'. $this->getgroup_id() .'">';
		//ici l'ajout du champ date
		$checked = $this->getDate() == null ? "" : "checked";
		echo '<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" name="isdate" role="switch" id="flexSwitchCheckDefault" '. $checked .'>
					<label class="form-check-label" for="flexSwitchCheckDefault">Appliquer la date de rendu</label>
				</div>';
		echo '<input type="date" name="date-r"
		value="'. $this->getDate() .'">';
		echo '<select class="form-select" name="user_id" aria-label="Default select example">';
		echo '<option value="-1">Non affectée</option>';
		$projet = $this->getProjet();
		if($projet->getUser_id()==User::getLogUser()->getId()){
			foreach (DB::getInstance()->getUsers() as $us) {
				if (Access::haveRight($us, $projet)) {
					echo '<option value="' . $us->getId() . '" ' . ($this->getUser_id() == $us->getId() ? "selected" : "") . '>' . $us->getFirstname() . " " . $us->getLastname() . '</option>';
				}
			}
		} else {
			$us = User::getLogUser();
			echo '<option value="' . $us->getId() . '" ' . ($this->getUser_id() == $us->getId() ? "selected" : "") . '>' . $us->getFirstname() . " " . $us->getLastname() . '</option>';
		}
		echo '</select>';
		//fin ici l'ajout du champ date
		echo '<input class="btn btn-outline-secondary" type="submit">';
		echo '</form>';
		//bouton modifier ? view ?
		echo '</div>';
		echo '</div>';
	}

	private function haveUpdateRight(){
		$db = DB::getInstance();
		foreach($db->getAllGroups() as $gr){
			if($gr->getId() == $this->getgroup_id()){
				foreach ($db->getProjets() as $pr) {
					//check si proprietaire du projet.
					if($pr->getId()==$gr->getProjet_id()){
						return $pr->getUser_id() == User::getLogUser()->getId() || ($this->getUser_id() == User::getLogUser()->getId() && Access::haveRight(User::getLogUser(), $pr));
					}
				}
			}
		}
	}

	/**
	 * Retourne le projet contenant cette tâche
	 * 
	 * @return Projet|null
	 */
	private function getProjet(){
		$db = DB::getInstance();
		foreach($db->getAllGroups() as $gr){
			if($gr->getId() == $this->getgroup_id()){
				foreach ($db->getProjets() as $pr) {
					//check si proprietaire du projet.
					if($pr->getId()==$gr->getProjet_id()){
						return $pr;
					}
				}
			}
		}
		return null;
	}
	/**
	 * Retourne le groupe contenant cette tâche
	 * 
	 * @return Group|null
	 */
	private function getGroup(){
		$db = DB::getInstance();
		foreach($db->getAllGroups() as $gr){
			if($gr->getId() == $this->getgroup_id()){
				return $gr;
			}
		}
		return null;
	}
	/**
	 * getLeftGroup retourne le groupe à gauche de celui contenant la tache
	 * s'il n'y en a pas alors NULL
	 * 
	 *	@return Group|null
	 */
	public function getLeftGroup(){
		$projet = $this->getProjet();
		if($projet == null){
			return null;
		}
		$group = $this->getGroup();
		if($group == null){
			return null;
		}
		$tmpgr = null;
		foreach(DB::getInstance()->getGroups($projet) as $gr){
			if($gr->getId()==$group->getId()){
				return $tmpgr;
			}
			$tmpgr = $gr;
		}
		//si on arrive la il y a une probleme (genre le projet est supprimé)
		return null;
	}
	/**
	 * getRightGroup retourne le groupe à droite de celui contenant la tache
	 * s'il n'y en a pas alors NULL
	 * 
	 *	@return Group|null
	 */
	public function getRightGroup(){
		$projet = $this->getProjet();
		if($projet == null){
			return null;
		}
		$group = $this->getGroup();
		if($group == null){
			return null;
		}
		$isnext = false;
		foreach(DB::getInstance()->getGroups($projet) as $gr){
			if($isnext == true){
				return $gr;
			}
			if($gr->getId()==$group->getId()){
				$isnext = true;
			}
		}
		//si on arrive la il y a une pas forcement un probleme (genre le projet est supprimé)
		return null;
	}
	
	/**
	 * remplace par un retour à la ligne chaque nouvelle balise <br> ou <br/>
	 *
	 * @param  mixed $string
	 * @return string
	 */
	private static function br2nl($str){
		return preg_replace('#<br\s*/?>#i', "", $str);
	}

	/**
	 * Summary of cmpalpha
	 * Compare name of $p1 and $p2
	 * with strcmp
	 * @param Task $p1
	 * @param Task $p2
	 * @return int
	 */
	public static function cmpalpha($p1, $p2){
		return strcmp($p1->getTitre(), $p2->getTitre());
	}
	/**
	 * Summary of cmpalpha2
	 * Compare name of $p1 and $p2
	 * with strcmp but inverted
	 * @param Task $p1
	 * @param Task $p2
	 * @return int
	 */
	public static function cmpalpha2($p1, $p2){
		return strcmp($p2->getTitre(), $p1->getTitre());
	}
	/**
	 * Summary of cmpDate
	 * Compare date of $p1 and $p2
	 * @param Task $p1
	 * @param Task $p2
	 * @return int
	 */
	public static function cmpDate($p1, $p2){
		if($p1->getDate() > $p2->getDate()){
			return 1; 
		}
		if($p1->getDate() == $p2->getDate()){
			return 0; 
		}
		//if($p1->getDate() < $p2->getDate()){
		return -1;
	}
	/**
	 * Summary of cmpDate2
	 * Compare date of $p1 and $p2
	 * but return the inverse
	 * @param Task $p1
	 * @param Task $p2
	 * @return int
	 */
	public static function cmpDate2($p1, $p2){
		if($p1->getDate() < $p2->getDate()){
			return 1; 
		}
		if($p1->getDate() == $p2->getDate()){
			return 0; 
		}
		//if($p1->getDate() > $p2->getDate()){
		return -1;
	}

	public function haveRight(){
		return (Access::haveRight(User::getLogUser(), $this->getProjet())&& (User::getLogUser()->getId()==$this->getUser_id()||User::getLogUser()->getId()==$this->getProjet()->getUser_id()));
	}
}
?>