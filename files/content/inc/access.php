<?php
require_once("based_config.php");
require_once('includes.php');
class Access{
    private $id;
	private $projet_id;
	private $user_id;
    private $read_only;

    public function __construct($id = "", $projet_id = "", $user_id = "", $read_only = ""){
        $this->id = $id;
        $this->projet_id = $projet_id;
        $this->user_id = $user_id;
        $this->read_only = $read_only;
    }

    public function getId(){
        return $this->id;
    }
    public function getProjet_id(){
        return $this->projet_id;
    }
    public function getUser_id(){
        return $this->user_id;
    }
    public function getRead_only(){
        return $this->read_only;
    }
    
    /**
     * @param User $user
     * @param Projet $projet
     * @return Bool
     */
    public static function haveRight($user, $projet){
        if($projet == null){
            return false;
        }
        if($user == null){
            return false;
        }
        $db = DB::getInstance();
        if($user->getId() == $projet->getUser_id()){
            return true;
        }
        $access = $db->getUserAccess($projet);
        foreach($access as $as){
            if($as->getUser_id()==$user->getId()){
                return true;
            }
        }
        return false;
    }
    public static function isInvited($user, $projet){
        $db = DB::getInstance();
        $access = $db->getUserAccess($projet);
        foreach($access as $as){
            if($as->getUser_id()==$user->getId()){
                return true;
            }
        }
        return false;
    }
}
?>