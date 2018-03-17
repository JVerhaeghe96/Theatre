<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 10-03-18
 * Time: 11:21
 */

class ConnexionManager extends AbstractManager
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
     * @return Connexion|String
     */
    function connect($login, $mdp){
        $sql = "SELECT * FROM administration WHERE rang=:login AND password=:mdp";
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
                $user = new Connexion($result);
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $user;
    }
}