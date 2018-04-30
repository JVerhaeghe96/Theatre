<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 23-03-18
 * Time: 21:40
 */

class PersonnelManager extends AbstractManager
{
    /**
     * PersonnelManager constructor.
     * @param $db PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $personnel Personnel
     * @param $titreSpectacle String
     * @return boolean
     */
    function ajouterPersonnel($personnel, $titreSpectacle){
        $sql = "INSERT INTO personnel (nom, prenom, fonction) VALUES (:nom, :prenom, :fonction)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":nom", $personnel->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $personnel->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":fonction", $personnel->getFonction(), PDO::PARAM_STR);

            $ok = $stmt->execute();
            $personnel->setId($this->db->lastInsertId());

            if($ok){
                $ok = $this->garnirOrganiser($this->db->lastInsertId(), $titreSpectacle);
            }

        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $personnel int
     * @param $titreSpectacle String
     * @return boolean
     */
    private function garnirOrganiser($personnel, $titreSpectacle){
        $sql = "INSERT INTO organiser (id, titre) VALUES (:id, :titre)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", $personnel, PDO::PARAM_INT);
            $stmt->bindValue(":titre", $titreSpectacle, PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }
}