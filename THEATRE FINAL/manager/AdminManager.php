<?php

namespace app\manager;

use app\model\User;

class AdminManager extends AbstractManager
{
    /**
     * ConnexionManager constructor.
     * @param $db \PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $login String
     * @param $mdp String
     * @return User|null
     */

    /* connexion*/
    function connect($login, $mdp){
        $sql = "SELECT * FROM administration WHERE login=:login AND password=PASSWORD(:mdp)";
        $stmt = null;
        $user = null;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":login", $login, \PDO::PARAM_STR);
            $stmt->bindValue(":mdp", $mdp, \PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch();   //  On récupère le résultat

            if($result == null){    //  Si la connexion a échoué
                $user = null;
            }else{  //  Si la connexion a réussi
                $user = new User($result);
            }
        }catch(\PDOException $e){
            $user = null;
        }finally{
            $stmt->closeCursor();
        }

        return $user;
    }

    /**
     * @param String $login
     * @param String $mdp
     * @return bool
     */
    function modifierMdp($login, $mdp){
        $sql = "UPDATE administration SET password=PASSWORD(:password) WHERE login=:login";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":password", $mdp, \PDO::PARAM_STR);
            $stmt->bindValue(":login", $login, \PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @return array
     */
    function getAllUsers(){
        $sql = "SELECT * FROM administration";
        $stmt = null;
        $users = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach ($result as $user){
                array_push($users, new User($user));
            }
        }catch(\PDOException $e){
            $users = array();
        }finally{
            $stmt->closeCursor();
        }
        return $users;
    }

    /**
     * @param $user String
     * @param $droit String
     * @param $valeur String
     */
    function modifierDroit($user, $droit, $valeur){
        $sql = "UPDATE administration SET ". $droit ."=:droits WHERE login=:login ";
        $stmt = null;
        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":droits",$valeur, \PDO::PARAM_STR);
            $stmt->bindValue(":login", $user, \PDO::PARAM_STR);
            $stmt->execute();
        }catch(\PDOException $e){

        }finally{
            $stmt->closeCursor();
        }

    }

    /**
     * @param $user String
     * @param $mdp String
     * @return boolean
     */
    function ajouterUser($user, $mdp){
        $sql = "INSERT INTO administration (login,password) VALUES (:login,PASSWORD(:mdp))";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":login",$user, \PDO::PARAM_STR);
            $stmt->bindValue(":mdp", $mdp, \PDO::PARAM_STR);
           $ok= $stmt->execute();
        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }
}