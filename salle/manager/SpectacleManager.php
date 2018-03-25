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

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $titres;
    }
}