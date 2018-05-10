<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 23-03-18
 * Time: 20:11
 */

class SpectacleManager extends AbstractManager
{
    /**
     * SpectacleManager constructor.
     * @param $db PDO
     *
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     */
    function getAllTitles(){
        $sql = "SELECT titre FROM spectacle";
        $stmt = null;
        $titres = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach($result as $titre){
                array_push($titres, $titre["titre"]);
            }

            array_splice($titres, 0, 1);

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $titres;
    }

    /**
     * @param $spectacle Spectacle
     * @return bool
     */
    function ajouterSpectacle($spectacle){
        $sql = "INSERT INTO spectacle (titre, resume) VALUES (:titre, :resume)";
        $stmt = null;
        $success = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":titre", $spectacle->getTitre(), PDO::PARAM_STR);
            $stmt->bindValue(":resume", $spectacle->getResume(), PDO::PARAM_STR);

            $success = $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $success;
    }
}