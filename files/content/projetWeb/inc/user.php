<?php
class User
{
	private $id;
	private $login;
	private $password;
    private $firstname;
    private $lastname;

	/**
	 * @param $id
	 * @param $login
	 * @param $password
     * @param $firstname
     * @param $lastname
	 */
	public function __construct($id="", $login="", $password="", $firstname="", $lastname="")
	{
		$this->id = $id;
		$this->login = $login;
		$this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
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
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}
    
    /**
	 * @return mixed
	 */
    public function getFirstname()
	{
		return $this->firstname;
	}
    /**
	 * @return mixed
	 */
    public function getLastname(){
        return $this->lastname;
    }
	/**
	 * @return bool
	 */
	public static function storeLogUser($us){
		session_regenerate_id();
		$_SESSION['user'] = serialize($us);
		return isset($_SESSION['user']) && $_SESSION['user']!=null;
	}
	/**
	 * @return bool
	 */
	public static function isLog(){
		return isset($_SESSION['user']) && $_SESSION['user']!=null;
	}
	/**
	 * @return null|User
	 */
	public static function getLogUser(){
		if (self::isLog()){
			return unserialize($_SESSION['user']);
		}
		return null;
	}
}
?>