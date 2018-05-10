<?php
class AdminManager extends AbstractManager
{
    /**
     * ConnexionManager constructor.
     * @param $db PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $login String
     * @param $mdp String
     * @return User|String
     */

    /* connexion*/
    function connect($login, $mdp){
        $sql = "SELECT * FROM administration WHERE login=:login AND password=:mdp";
        $stmt = null;
        $user = null;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":login", $login, PDO::PARAM_STR);
            $stmt->bindValue(":mdp", $mdp, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch();   //  On récupère le résultat

            if($result == null){    //  Si la connexion a échoué
                $user = "Nom d'utilisateur ou mot de passe incorrect";
            }else{  //  Si la connexion a réussi
                $user = new User($result);
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $user;
    }

    /*avoir un utilisateur*/
    function getUser($login){
        $sql = "SELECT * FROM administration WHERE login=:login ";
        $stmt = null;
        $user = null;
        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":login", $login, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch();   //  On récupère le résultat

            if($result != null)   // connexion
                $user =  new User($result);

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $user;
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
        }catch(PDOException $e){
            die($e->getMessage());
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

            $stmt->bindValue(":droits",$valeur, PDO::PARAM_STR);
            $stmt->bindValue(":login", $user, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

    }

    /**
     * @param $user String
     * @param $mdp String
     */
    function ajouterUser($user, $mdp){
        $sql = "INSERT INTO administration (login,password) VALUES (:login,:mdp)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":login",$user, PDO::PARAM_STR);
            $stmt->bindValue(":mdp", $mdp, PDO::PARAM_STR);
           $ok= $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }
}