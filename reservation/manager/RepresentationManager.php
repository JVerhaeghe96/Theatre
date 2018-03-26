<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 23/03/2018
 * Time: 20:11
 */

class RepresentationManager extends AbstractManager
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
     * @param $representation Representation
     */
    function ajouterRepresentation($representation){
        $sql = "INSERT INTO representation (date,titre) VALUES (:date,:titre)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":date",DateUtils::getDateForPDO(DateUtils::createDateTime($representation->getDate())),PDO::PARAM_STR);
            $stmt->bindValue(":titre", $representation->getTitre(), PDO::PARAM_STR);
            $ok= $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    function getAllDatesByTitle($titre){
        $sql = "SELECT date FROM representation WHERE titre=:titre";
        $stmt = null;
        $dates = array();
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach($result as $date){
                array_push($dates, $date["date"]);
            }

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $dates;
    }

    function getAllDates(){
        $sql = "SELECT date FROM representation";
        $stmt = null;
        $dates = array();

        try{
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dates = $stmt->fetchAll();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $dates;
    }


}