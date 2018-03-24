<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 24/03/2018
 * Time: 09:19
 */

class SpectatorManager
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
     * @param $spectator Spectator
     */
    function ajouterSpectator($spectator){
        $sql = "INSERT INTO spectateurs (nom,prenom,telFixe,telMobile,adresseMail,commentaire) VALUES (:nom,:prenom,:telFixe,:telMobile,:adresseMail,:commentaire)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":nom",$spectator->getNom(),PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $spectator->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":telFixe", $spectator->getTelFixe(), PDO::PARAM_INT);
            $stmt->bindValue(":telMobile", $spectator->getTelMobile(), PDO::PARAM_INT);
            $stmt->bindValue(":adresseMail", $spectator->getAdresseMail(), PDO::PARAM_STR);
            $stmt->bindValue(":commentaire", $spectator->getCommentaire(), PDO::PARAM_STR);
            $ok= $stmt->execute();
            $spectator->setId($this->db->lastInsertId());
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @param $spectator Spectator
     */
    function ajouterAdresse($spectator)
    {
        $sql = "INSERT INTO adresse (id,rue,numero,localite,codePostal) VALUES (:id,:rue,:numero,:localite,:codePostal)";

        $stmt = null;
        $ok = false;
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":rue", $spectator->getRue(), PDO::PARAM_STR);
            $stmt->bindValue(":numero", $spectator->getNumero(), PDO::PARAM_INT);
            $stmt->bindValue(":localite", $spectator->getLocalite(), PDO::PARAM_STR);
            $stmt->bindValue(":codePostal", $spectator->getCodePostal(), PDO::PARAM_INT);
            $ok = $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
        return $ok;
    }


    /**
     * @return array
     */
    function getAllSpectators(){
        $sql = "SELECT nom,prenom,id FROM spectateurs";
        $stmt = null;
        $spectators = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach ($result as $spectator){
                array_push($spectators, new Spectator($spectator));
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $spectators;
    }

    /**
     * @param $id
     * @return null|array
     */
    function getSpectatorById($id){
        $sql = "SELECT * FROM spectateurs INNER JOIN adresse ON spectateurs.id=adresse.id WHERE spectateurs.id=:id ";
        $stmt = null;
        $result = null;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id",$id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $result;
    }

    function modifierSpectatorById($spectator){
        $sql="UPDATE  spectateurs SET telFixe=:telFixe,telMobile=:telMobile,adresseMail=:adresseMail,commentaire=:commentaire WHERE id=:id";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":telFixe", $spectator->getTelFixe(), PDO::PARAM_INT);
            $stmt->bindValue(":telMobile", $spectator->getTelMobile(), PDO::PARAM_INT);
            $stmt->bindValue(":adresseMail", $spectator->getAdresseMail(), PDO::PARAM_STR);
            $stmt->bindValue(":commentaire", $spectator->getCommentaire(), PDO::PARAM_STR);
            $ok=$stmt->execute();

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    function modifierAdresseById($spectator){
        $sql="UPDATE adresse SET rue=:rue,numero=:numero,localite=:localite,codePostal=:codePostal WHERE id=:id";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":rue", $spectator->getRue(), PDO::PARAM_STR);
            $stmt->bindValue(":numero", $spectator->getNumero(), PDO::PARAM_INT);
            $stmt->bindValue(":localite", $spectator->getLocalite(), PDO::PARAM_STR);
            $stmt->bindValue(":codePostal", $spectator->getCodePostal(), PDO::PARAM_INT);
            $ok=$stmt->execute();

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }
}


