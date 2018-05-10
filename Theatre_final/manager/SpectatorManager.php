<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 24/03/2018
 * Time: 09:19
 */

namespace app\manager;

use app\model\Spectator;

class SpectatorManager
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
     * @param $spectator Spectator
     * @return boolean
     */
    function ajouterSpectator($spectator){
        $sql = "INSERT INTO spectateurs (nom,prenom,telFixe,telMobile,adresseMail,commentaire) VALUES (:nom,:prenom,:telFixe,:telMobile,:adresseMail,:commentaire)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":nom",$spectator->getNom(),\PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $spectator->getPrenom(), \PDO::PARAM_STR);
            $stmt->bindValue(":telFixe", $spectator->getTelFixe(), \PDO::PARAM_INT);
            $stmt->bindValue(":telMobile", $spectator->getTelMobile(), \PDO::PARAM_INT);
            $stmt->bindValue(":adresseMail", $spectator->getAdresseMail(), \PDO::PARAM_STR);
            $stmt->bindValue(":commentaire", $spectator->getCommentaire(), \PDO::PARAM_STR);
            $ok= $stmt->execute();
            $spectator->setId($this->db->lastInsertId());
        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @param $spectator Spectator
     * @return boolean
     */
    function ajouterAdresse($spectator)
    {
        $sql = "INSERT INTO adresse (id,rue,numero,localite,codePostal) VALUES (:id,:rue,:numero,:localite,:codePostal)";

        $stmt = null;
        $ok = false;
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(":rue", $spectator->getRue(), \PDO::PARAM_STR);
            $stmt->bindValue(":numero", $spectator->getNumero(), \PDO::PARAM_INT);
            $stmt->bindValue(":localite", $spectator->getLocalite(), \PDO::PARAM_STR);
            $stmt->bindValue(":codePostal", $spectator->getCodePostal(), \PDO::PARAM_INT);
            $ok = $stmt->execute();
        } catch (\PDOException $e) {
            $ok = false;
        } finally {
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @return array
     */
    function getAllSpectatorsMails(){
        $sql = 'SELECT adresseMail FROM spectateurs where adresseMail NOT LIKE ""';
        $stmt = null;
        $tabMail = array();

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $mail){
                array_push($tabMail, $mail["adresseMail"]);
            }
        }catch(\PDOException $e){
            $tabMail = array();
        }finally{
            $stmt->closeCursor();
        }

        return $tabMail;

    }


    /**
     * @return array|null
     */
    function getAllSpectators(){
        $sql = "SELECT * FROM spectateurs";
        $stmt = null;
        $result = null;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();
        }catch(\PDOException $e){
            $result = null;
        }finally{
            $stmt->closeCursor();
        }

        return $result;
    }

    /**
     * @param $spectateur Spectator
     * @return null|array
     */
    function getSpectatorByNomPrenom($spectateur){
        $sql = "SELECT * FROM spectateurs INNER JOIN adresse ON spectateurs.id=adresse.id WHERE spectateurs.nom=:nom AND spectateurs.prenom=:prenom";
        $stmt = null;
        $result = null;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":nom",$spectateur->getNom(), \PDO::PARAM_STR);
            $stmt->bindValue(":prenom",$spectateur->getPrenom(), \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

        }catch(\PDOException $e){
            $result = null;
        }finally{
            $stmt->closeCursor();
        }
        return $result;
    }

    /**
     * @param Spectator $spectator
     * @return bool
     */
    function modifierSpectatorById($spectator){
        $sql="UPDATE  spectateurs SET telFixe=:telFixe,telMobile=:telMobile,adresseMail=:adresseMail,commentaire=:commentaire WHERE id=:id";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(":telFixe", $spectator->getTelFixe(), \PDO::PARAM_INT);
            $stmt->bindValue(":telMobile", $spectator->getTelMobile(), \PDO::PARAM_INT);
            $stmt->bindValue(":adresseMail", $spectator->getAdresseMail(), \PDO::PARAM_STR);
            $stmt->bindValue(":commentaire", $spectator->getCommentaire(), \PDO::PARAM_STR);
            $ok=$stmt->execute();

        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @param Spectator $spectator
     * @return bool
     */
    function modifierAdresseById($spectator){
        $sql="UPDATE adresse SET rue=:rue,numero=:numero,localite=:localite,codePostal=:codePostal WHERE id=:id";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $spectator->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(":rue", $spectator->getRue(), \PDO::PARAM_STR);
            $stmt->bindValue(":numero", $spectator->getNumero(), \PDO::PARAM_INT);
            $stmt->bindValue(":localite", $spectator->getLocalite(), \PDO::PARAM_STR);
            $stmt->bindValue(":codePostal", $spectator->getCodePostal(), \PDO::PARAM_INT);
            $ok=$stmt->execute();

        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @return array|null
     */
    function autocompleteNom(){

        $term=$_GET['term'];
        $sql = "SELECT nom FROM spectateurs WHERE nom LIKE :term";
        $stmt = null;
        $result=null;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":term",'%'.$term.'%', \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
        }catch(\PDOException $e){
            $result = null;
        }finally{
            $stmt->closeCursor();
        }
        return $result;
    }

    /**
     * @return array|null
     */
    function autocompletePrenom(){

        $term=$_GET['term'];
        $sql = "SELECT prenom FROM spectateurs WHERE prenom LIKE :term";
        $stmt = null;
        $result=null;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":term",'%'.$term.'%', \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
        }catch(\PDOException $e){
            $result = null;
        }finally{
            $stmt->closeCursor();
        }
        return $result;
    }

}


