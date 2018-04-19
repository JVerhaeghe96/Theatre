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
        $sql = "INSERT INTO representation (date,heure,titre) VALUES (:date,:heure,:titre)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":date",DateUtils::getDateForPDO(DateUtils::createDateTime($representation->getDate())),PDO::PARAM_STR);
            $stmt->bindValue(":heure",$representation->getHeure(),PDO::PARAM_STR);
            $stmt->bindValue(":titre", $representation->getTitre(), PDO::PARAM_STR);
            $ok= $stmt->execute();
        }catch(PDOException $e){
            //  Utile pour le test, nocif pour le bon fonctionnement de l'application
//            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

    /**
     * @return array
     */
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

    /**
     * @param $titre String
     * @return array
     */
    function getAllDatesByTitle($titre){
        $sql = "SELECT r.date,r.heure, count(1) AS nbPlacesDispo
                FROM representation AS r 
                INNER JOIN chaise AS c ON c.date = r.date 
                WHERE titre=:titre 
                AND c.heure=r.heure
                AND c.etat = 'D'
                GROUP BY r.date";
        $stmt = null;
        $dates = array();
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($result as $date){
                array_push($dates, $date);
            }

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $dates;
    }

    function getAllRepresentationsByTitle($titre)
    {
        $sql = "SELECT r.date,r.heure
                FROM representation AS r 
                INNER JOIN chaise AS c ON c.date = r.date 
                WHERE titre=:titre 
                AND c.heure=r.heure
                GROUP BY r.date";
        $stmt = null;
        $dates = array();
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $date) {
                array_push($dates, $date);
            }

        } catch (PDOException $e) {
            die($e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
        return $dates;
    }

}