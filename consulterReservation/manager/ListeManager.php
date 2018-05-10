<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 09/04/2018
 * Time: 15:37
 */

class ListeManager extends  AbstractManager
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
     * @param $spectateur Spectator
     */
    function getReservationByNom($spectateur,$date,$heure){
    $sql = "SELECT s.nom,s.prenom, r.id as resId, r.nbSieges,c.id as cId
                from spectateurs as s 
                INNER JOIN reservations as r ON s.id=r.SpecId
                INNER JOIN chaise as c ON c.ResId=r.id
                WHERE nom = :nom AND prenom=:prenom AND date=:date AND  heure=:heure";
    $stmt = null;
    $result=false;

    try{
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":nom",$spectateur->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(":prenom",$spectateur->getPrenom(), PDO::PARAM_STR);
        $stmt->bindValue(":date",$date,PDO::PARAM_STR);
        $stmt->bindValue(":heure",$heure,PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

    }catch(PDOException $e){
        die($e->getMessage());
    }finally{
        $stmt->closeCursor();
    }
    return $result;
    }

    /**
     * @param $id int
     */
    function getReservationByNumReservation($id){
        $sql = "SELECT s.nom,s.prenom, r.id as resId, r.nbSieges,c.id as cId
                from spectateurs as s 
                INNER JOIN reservations as r ON s.id=r.SpecId
                INNER JOIN chaise as c ON c.ResId=r.id
                WHERE r.id = :id ";
        $stmt = null;
        $result=null;

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id",$id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll();

        }catch(PDOException $e){
            die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $result;
    }

}