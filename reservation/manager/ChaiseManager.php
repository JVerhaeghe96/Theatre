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
        $sql ="INSERT INTO chaise (date, id) VALUES (:date, :id)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db->prepare($sql);

            $date = DateUtils::getDateForPDO(DateUtils::createDateTime($chaise->getDate()));

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $id String
     * @return Chaise|null
     */
    function getByIdAndDate($id, $date){
        $sql = 'SELECT * FROM chaise WHERE id=:id AND date=:date';
        $stmt = null;
        $chaise = null;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($date));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_STR);
            $stmt->bindValue(":date", $date, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch();

            $chaise = new Chaise($result);
        }catch(PDOException $e){
            die($e->getMessage());
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
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $date
     * @return array
     */
    function getAllByDates($date){
        $sql = "SELECT date, id, etat, ResId from chaise WHERE date = :date ORDER BY tri ASC";
        $stmt = null;
        $chaises = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll();

            foreach($result as $chaise){
                array_push($chaises, new Chaise($chaise));
            }
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $chaises;
    }

    /**
     * @param $currentDate String
     * @param $newDate String
     * @return bool
     */
    function copierPlanSalle($currentDate, $newDate){
        $sql = "insert into chaise (date, id, etat)
                select :newDate, id, etat
                from chaise
                where date = :currentDate
                order by tri asc";
        $stmt = null;
        $ok = false;

        $currentDate = DateUtils::getDateForPDO(DateUtils::createDateTime($currentDate));
        $newDate = DateUtils::getDateForPDO(DateUtils::createDateTime($newDate));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":newDate", $newDate, PDO::PARAM_STR);
            $stmt->bindValue(":currentDate", $currentDate, PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param $date String
     * @return bool
     */
    function deleteAllByDate($date){
        $sql = "DELETE FROM chaise WHERE date = :date";
        $stmt = null;
        $ok = false;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($date));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            die($e->getMessage());
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
        $sql = 'UPDATE chaise SET etat = :etat, ResId =:ResId WHERE id= :id AND date=:date';
        $stmt = null;
        $ok = false;

        $date = DateUtils::getDateForPDO(DateUtils::createDateTime($chaise->getDate()));

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":etat", $chaise->getEtat(), PDO::PARAM_STR);
            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);
            $stmt->bindValue(":ResId", $chaise->getResId(), PDO::PARAM_INT);
            $ok = $stmt->execute();
        }catch(PDOException $e){
           // die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

}