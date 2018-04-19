<?php


class ReservationManager extends  AbstractManager
{
    /**
     * ReservationManager constructor.
     * @param $db PDO
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $reservation Reservation
     * @return boolean
     */
    function ajouterReservation($reservation){
        $sql = "INSERT INTO reservations (nbSieges,remarque,SpecId,titre) VALUES (:nbSieges,:remarque,:SpecId,:titre)";

        $stmt = null;
        $ok=false;
        try{
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":nbSieges",$reservation->getNbSieges(), PDO::PARAM_INT);
            $stmt->bindValue(":remarque", $reservation->getRemarque(), PDO::PARAM_STR);
            $stmt->bindValue(":SpecId", $reservation->getSpecId(), PDO::PARAM_INT);
            $stmt->bindValue(":titre", $reservation->getTitre(), PDO::PARAM_STR);
            $ok= $stmt->execute();
        }catch(PDOException $e){
         //   die($e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
        return $ok;
    }

}