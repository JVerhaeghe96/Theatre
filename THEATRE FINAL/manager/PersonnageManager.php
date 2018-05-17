<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 24-03-18
 * Time: 09:48
 */

namespace app\manager;

use app\model\Personnage;

class PersonnageManager extends AbstractManager
{
    /**
     * PersonnageManager constructor.
     * @param $db \PDO
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
        $sql = "INSERT INTO personnages (titre, id, nom, prenom) VALUES (:titre, :id, :nom, :prenom)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":titre", $personnage->getTitre(), \PDO::PARAM_STR);
            $stmt->bindValue(":id", $personnage->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(":nom", $personnage->getNom(), \PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $personnage->getPrenom(), \PDO::PARAM_STR);


            $ok = $stmt->execute();
        }catch(\PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }
        var_dump($personnage);
        return $ok;
    }
}