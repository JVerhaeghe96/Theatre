<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 24-03-18
 * Time: 09:48
 */

class PersonnageManager extends AbstractManager
{
    /**
     * PersonnageManager constructor.
     * @param $db PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $personnage Personnage
     * @return boolean
     */
    function ajouterPersonnage($personnage){
        $sql = "INSERT INTO personnages (titre, nom, persNom, persPrenom, prenom) VALUES (:titre, :nom, :persNom, :persPrenom, :prenom)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":titre", $personnage->getTitre(), PDO::PARAM_STR);
            $stmt->bindValue(":nom", $personnage->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(":persNom", $personnage->getPersNom(), PDO::PARAM_STR);
            $stmt->bindValue(":persPrenom", $personnage->getPersPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $personnage->getPrenom(), PDO::PARAM_STR);


            $ok = $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }
}