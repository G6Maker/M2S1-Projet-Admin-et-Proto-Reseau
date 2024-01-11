<?php
require_once KANBAN_CONFIG."/config_db.php";
require_once "task.php";
require_once "group.php";
require_once "projet.php";
require_once "user.php";
require_once "access.php";
//inclure les tabs

class DB
{
	private static $instance = null; //mémorisation de l'instance de DB pour appliquer le pattern Singleton
	private $connect = null; //connexion PDO à la base

	/************************************************************************/
	//  Constructeur gerant  la connexion à la base via PDO
	//  NB : il est non utilisable a l'exterieur de la classe DB
	/************************************************************************/
	private function __construct()
	{
		// Connexion à la base de données
		$connStr = "mysql:host=". DB_HOSTNAME .";port=". DB_PORT .";dbname=" . DB_DB;
		try
		{
			// Connexion à la base
			$this->connect = new PDO($connStr, DB_USERNAME, DB_PASSWORD);
			// Configuration facultative de la connexion
			$this->connect->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
			$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			echo "probleme de connexion :" . $e->getMessage();
			return;
		}
	}

	/************************************************************************/
	//  Methode permettant d'obtenir un objet instance de DB
	//  NB : cet objet est unique pour l'exécution d'un même script PHP
	//  NB2: c'est une methode de classe.
	/************************************************************************/
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			try
			{
				self::$instance = new DB();
			}
			catch (PDOException $e)
			{
				echo $e;
			}
		}
		$obj = self::$instance;

		if (($obj->connect) == null)
		{
			self::$instance = null;
		}
		return self::$instance;
	}

	/************************************************************************/
	//  Methode permettant de fermer la connexion a la base de données
	/************************************************************************/
	public function close()
	{
		$this->connect = null;
	}

	/************************************************************************/
	//  Methode uniquement utilisable dans les méthodes de la class DB
	//  permettant d'exécuter n'importe quelle requête SQL
	//  et renvoyant en résultat les tuples renvoyés par la requête
	//  sous forme d'un tableau d'objets
	//  param1 : texte de la requête à exécuter (éventuellement paramétrée)
	//  param2 : tableau des valeurs permettant d'instancier les paramètres de la requête
	//  NB : si la requête n'est pas paramétrée alors ce paramètre doit valoir null.
	//  param3 : nom de la classe devant être utilisée pour créer les objets qui vont
	//  représenter les différents tuples.
	//  NB : cette classe doit avoir des attributs qui portent le même que les attributs
	//  de la requête exécutée.
	//  ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//  que d'éléments dans le tableau passé en second paramètre.
	//  NB : si la requête ne renvoie aucun tuple alors la fonction renvoie un tableau vide
	/************************************************************************/
	private function execQuery($requete, $tparam, $nomClasse)
	{
		//on prépare la requête
		$stmt = $this->connect->prepare($requete);
		//on indique que l'on va récupére les tuples sous forme d'objets instance
		$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $nomClasse);
		//on exécute la requête

		if ($tparam != null)
		{
			$stmt->execute($tparam);
		}
		else
		{
			$stmt->execute();
		}

		//récupération du résultat de la requête sous forme d'un tableau d'objets
		$tab = array();
		$tuple = $stmt->fetch(); //on récupère le premier tuple sous forme d'objet
		if ($tuple)
		{
			//au moins un tuple a été renvoyé
			while ($tuple != false)
			{
				$tab[] = $tuple; //on ajoute l'objet en fin de tableau
				$tuple = $stmt->fetch(); //on récupère un tuple sous la forme
				//d'un objet instance de la classe $nomClasse
			}
		}
		return $tab;
	}

	/************************************************************************/
	//  Methode utilisable uniquement dans les méthodes de la classe DB
	//  permettant d'exécuter n'importe quel ordre SQL (update, delete ou insert)
	//  autre qu'une requête.
	//  Résultat : nombre de tuples affectés par l'exécution de l'ordre SQL
	//  param1 : texte de l'ordre SQL à exécuter (éventuellement paramétré)
	//  param2 : tableau des valeurs permettant d'instancier les paramètres de l'ordre SQL
	//  ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//  que d'éléments dans le tableau passé en second paramètre.
	/************************************************************************/
	private function execMaj($ordreSQL, $tparam)
	{
		try
		{
			$stmt = $this->connect->prepare($ordreSQL);
			$res = $stmt->execute($tparam);
			return $stmt->rowCount();
		}
		catch (PDOException $e)
		{
			echo $e->getMessage( );
			return false;
		}

	}
	
	// Fonction qui retourne un boolean si presence ou non de l'élément cherché
	private function existe($ordreSQL, $tparam)
	{
		$stmt = $this->connect->prepare($ordreSQL);
		$res = $stmt->execute($tparam); //execution de l'ordre SQL
		return $stmt->rowCount() > 0;
	}

	/*************************************************************************
	 * Fonctions qui peuvent être utilisées dans les scripts PHP
	 *************************************************************************/

    /**
     * @param Group $group
     * @return array of Task
     */
	public function getTasks($group)
	{
		$requete = 'select * from kanban_task where group_id = ?';
		return $this->execQuery($requete, array($group->getId()), 'Task');
	}
	public function getAllTasks()
	{
		$requete = 'select * from kanban_task';
		return $this->execQuery($requete, null, 'Task');
	}
	public function getTask($task)
	{
		$requete = 'select * from kanban_task where id = ?';
		return $this->execQuery($requete, array($task->getId()), 'Task');
	}
    /**
     * @param Projet $projet
     */
	public function getGroups($projet)
	{
		$requete = 'select * from kanban_group where projet_id = ?';
		return $this->execQuery($requete, array($projet->getId()), 'Group');
	}
	public function getAllGroups()
	{
		$requete = 'select * from kanban_group';
		return $this->execQuery($requete, null, 'Group');
	}

	public function getProjets()
	{
		$requete = 'select * from kanban_projet';
		return $this->execQuery($requete, null, 'Projet');
	}
	public function getProjet($projet)
	{
		$requete = 'select * from kanban_projet where id = ?';
		return $this->execQuery($requete, array($projet->getId()), 'Projet');
	}

	public function getUsers()
	{
		$requete = 'select * from kanban_users';
		return $this->execQuery($requete, null, 'User');
	}
	public function getUser($user)
	{
		$requete = 'select * from kanban_users where id = ?';
		return $this->execQuery($requete, array($user->getId()), 'User');
	}
	public function getAllAccess()
	{
		$requete = 'select * from kanban_projet_access';
		return $this->execQuery($requete, null, 'Access');
	}
	public function getUserAccess($projet)
	{
		$requete = 'select * from kanban_projet_access where projet_id = ?';
		return $this->execQuery($requete, array($projet->getId()), 'Access');
	}
	
    /**
     * @param User $user
     */
	public function insertUser($user)
	{
        $users = $this->getUsers();
        //verifie la non precence de deux même login.
        foreach($users as $us){
            if($us->getLogin() == $user->getLogin()){
                return false;
            }
        }
		$requete = 'INSERT INTO kanban_users(login, password, firstname, lastname) VALUES (?, ?, ?, ?)';
		$tParam = array($user->getLogin(),
                        $user->getPassword(),
                        $user->getFirstname(),
                        $user->getLastname());
		return $this->execMaj($requete, $tParam);
	}
	/**
     * @param User $user
     */
	public function updateUser($user)
	{
        $users = $this->getUsers();
		$requete = 'UPDATE kanban_users 
		SET login = ?, password = ?, firstname = ?, lastname = ?
		where id = ?';
		$tParam = array($user->getLogin(),
                        $user->getPassword(),
                        $user->getFirstname(),
                        $user->getLastname(),
						$user->getId());
		return $this->execMaj($requete, $tParam);
	}

    /**
     * @param User $user
     */
	public function deleteUser($user)
	{
		$requete = 'delete from kanban_users where id = ? and login = ?';
		$tparam = array($user->getId(),
                        $user->getLogin());
		return$this->execMaj($requete,$tparam);
	}

    /**
     * @param Task $task
     */
    public function insertTask($task)
	{
		$requete = 'INSERT INTO kanban_task(group_id, titre, description, user_id, date) VALUES (?, ?, ?, ?, ?)';
		$tParam = array($task->getgroup_id(),
                        $task->getTitre(),
                        $task->getDescription(),
                        $task->getUser_id(),
                        $task->getDate());
		return $this->execMaj($requete, $tParam);
	}
	public function updateTask($task)
	{
		$requete = 'UPDATE kanban_task
		SET group_id = ?, titre = ?, description = ?, user_id = ?, date = ?
		where id = ?';
		$tParam = array($task->getgroup_id(),
                        $task->getTitre(),
                        $task->getDescription(),
                        $task->getUser_id(),
                        $task->getDate(),
						$task->getId());
		return $this->execMaj($requete, $tParam);
	}

    /**
     * @param Task $task
     */
	public function deleteTask($task)
	{
		$requete = 'delete from kanban_task where id = ?';
		$tparam = array($task->getId());
		return$this->execMaj($requete,$tparam);
	}
    /**
     * @param Group $group
     */
    public function insertGroup($group)
	{
		$requete = 'INSERT INTO kanban_group(projet_id, name, date) VALUES (?, ?, ?)';
		$tParam = array($group->getProjet_id(),
                        $group->getName(),
                        $group->getDate());
		return $this->execMaj($requete, $tParam);
	}

	/**
     * @param Group $group
     */
    public function updateGroup($group)
	{
		$requete = 'UPDATE kanban_group
		SET projet_id = ?, name = ?, date = ?
		WHERE id  = ?';
		$tParam = array($group->getProjet_id(),
                        $group->getName(),
                        $group->getDate(),
						$group->getId());
		return $this->execMaj($requete, $tParam);
	}

    /**
     * @param Group $group
     */
	public function deleteGroup($group)
	{
		$requete = 'delete from kanban_group where id = ?';
		$tparam = array($group->getId());
		return$this->execMaj($requete,$tparam);
	}

    /**
     * @param Projet $projet
     */
    public function insertProjet($projet)
	{
		$requete = 'INSERT INTO kanban_projet(user_id, name, date, public) VALUES (?, ?, ?, ?)';
		$tParam = array($projet->getUser_id(),
                        $projet->getName(),
                        $projet->getDate(),
                        $projet->getPublic());
		return $this->execMaj($requete, $tParam);
	}

	/**
     * @param Projet $projet
     */
    public function updateProjet($projet)
	{
		$requete = 'UPDATE kanban_projet
		SET user_id = ?, name = ?, date = ?, public = ?
		WHERE id = ?';
		$tParam = array($projet->getUser_id(),
                        $projet->getName(),
                        $projet->getDate(),
                        $projet->getPublic(),
						$projet->getId());
		return $this->execMaj($requete, $tParam);
	}

    /**
     * @param Projet $projet
     */
	public function deleteProjet($projet)
	{
		$requete = 'delete from kanban_projet where id = ?';
		$tparam = array($projet->getId());
		return$this->execMaj($requete,$tparam);
	}
	/**
     * @param Access $access
     */
    public function insertAccess($access)
	{
		$requete = 'INSERT INTO kanban_projet_access( projet_id, user_id, read_only) VALUES (?, ?, ?)';
		$tParam = array($access->getProjet_id(),
                        $access->getUser_id(),
                        $access->getRead_only());
		return $this->execMaj($requete, $tParam);
	}
	/**
     * @param Access $access
     */
	public function updateAccess($access)
	{
		$requete = 'UPDATE kanban_projet_access
		SET projet_id = ?, user_id = ?, read_only = ?
		where id = ?';
		$tParam = array($access->getProjet_id(),
                        $access->getUser_id(),
                        $access->getRead_only(),
                        $access->getId());
		return $this->execMaj($requete, $tParam);
	}

    /**
     * @param Access $access
     */
	public function deleteAccess($access)
	{
		$requete = 'delete from kanban_projet_access where id = ?';
		$tparam = array($access->getId());
		return$this->execMaj($requete,$tparam);
	}
	
    //pas ok
	public function existeElement($table, $condition)
	{
		$requete = 'select * from ? where id';
		return $this->existe($requete, array($table, $condition));
		
	}
}

?>