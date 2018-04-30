<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 24-03-18
 * Time: 15:31
 */


class ChaiseManager extends AbstractManager
{

    /**
     * ChaiseManager constructor.
     * @param $db PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $chaise Chaise
     * @return bool
     */
    function ajouterChaise($chaise){
        $sql ="INSERT INTO chaise (date, id,heure) VALUES (:date, :id,:heure)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $date = DateUtils::getDateForPDO(DateUtils::createDateTime($chaise->getDate()));

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);
            $stmt->bindValue(":heure", $chaise->getHeure(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $id String
     * @return Chaise|null
     */
    function getByIdAndDate($id, $date,$heure){
        $sql = 'SELECT * FROM chaise WHERE id=:id AND date=:date AND heure=:heure';
        $stmt = null;
        $chaise = null;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($date));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_STR);
            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":heure", $heure, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch();

            $chaise = new Chaise($result);
        }catch(PDOException $e){
            $chaise = array();
        }finally{
            $stmt->closeCursor();
        }

        return $chaise;
    }

    /**
     * @param $chaise Chaise
     * @return boolean
     */
    function modifierEtat($chaise){
        $sql = 'UPDATE chaise SET etat = :etat WHERE id= :id AND date=:date';
        $stmt = null;
        $ok = false;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($chaise->getDate()));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":etat", $chaise->getEtat(), PDO::PARAM_STR);
            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $date
     * @return array
     */
    function getAllByDates($date,$heure){
        $sql = "SELECT * from chaise WHERE date = :date AND  heure=:heure ORDER BY tri ASC";
        $stmt = null;
        $chaises = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":heure", $heure, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll();

            foreach($result as $chaise){
                array_push($chaises, new Chaise($chaise));
            }
        }catch(PDOException $e){
            $chaises = array();
        }finally{
            $stmt->closeCursor();
        }

        return $chaises;
    }

    /**
     * @param Chaise $chaise
     * @return bool
     */
    function copierPlanSalle($chaise){
        $sql = "insert into chaise (date, heure, id, etat) values (:date, :heure, :id, :etat)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":date", $chaise->getDate(), PDO::PARAM_STR);
            $stmt->bindValue(":heure", $chaise->getHeure(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);
            $stmt->bindValue(":etat", $chaise->getEtat(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $date
     * @param $heure
     * @return bool
     */
    function deleteAllByDate($date, $heure){
        $sql = "DELETE FROM chaise WHERE date = :date AND heure = :heure";
        $stmt = null;
        $ok = false;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($date));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":heure", $heure, PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $chaise Chaise
     * @return bool
     */
    function reserverChaise($chaise){
        $sql = 'UPDATE chaise SET etat = :etat, ResId =:ResId WHERE id= :id AND date=:date AND heure=:heure';
        $stmt = null;
        $ok = false;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($chaise->getDate()));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":etat", $chaise->getEtat(), PDO::PARAM_STR);
            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":heure", $chaise->getHeure(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);
            $stmt->bindValue(":ResId", $chaise->getResId(), PDO::PARAM_INT);
            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

}