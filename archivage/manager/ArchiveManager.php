<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 27-04-18
 * Time: 22:35
 */

class ArchiveManager extends AbstractManager
{
    /**
     * ArchiveManager constructor.
     * @param $db_archive PDO
     */
    function __construct($db_archive)
    {
        $this->db_archive = $db_archive;
    }

    /**
     * @param Spectacle $spectacle
     * @return bool
     */
    function ajouterSpectacle($spectacle){
        $sql = "insert into spectacle (titre, resume) values (:titre, :resume)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":titre", $spectacle->getTitre(), PDO::PARAM_STR);
            $stmt->bindValue(":resume", $spectacle->getResume(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Personnel $personnel
     * @param $titreSpectacle
     * @return bool
     */
    function ajouterPersonnel($personnel, $titreSpectacle){
        $sql = "INSERT INTO personnel (id, nom, prenom, fonction) VALUES (:id, :nom, :prenom, :fonction)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":id", $personnel->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":nom", $personnel->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $personnel->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":fonction", $personnel->getFonction(), PDO::PARAM_STR);

            $ok = $stmt->execute();

            if($ok){
                $ok = $this->garnirOrganiser($personnel->getId(), $titreSpectacle);
            }
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param int $id
     * @param String $titre
     * @return bool
     */
    private function garnirOrganiser($id, $titre){
        $sql = "insert into organiser (id, titre) values (:id, :titre)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Personnage $personnage
     * @return bool
     */
    function ajouterPersonnages($personnage){
        $sql = "insert into personnages (titre, nom, prenom, id) values (:titre, :nom, :prenom, :id)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":titre", $personnage->getTitre(), PDO::PARAM_STR);
            $stmt->bindValue(":nom", $personnage->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $personnage->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $personnage->getId(), PDO::PARAM_INT);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Representation $representation
     * @return bool
     */
    function ajouterRepresentation($representation){
        $sql = "insert into representation (date, heure, titre) values (:date, :heure, :titre)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":date", $representation->getDate(), PDO::PARAM_STR);
            $stmt->bindValue(":heure", $representation->getHeure(), PDO::PARAM_STR);
            $stmt->bindValue(":titre", $representation->getTitre(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch (PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Spectator $spectateur
     * @return bool
     */
    function ajouterSpectateur($spectateur){
        $sql = "insert into spectateurs (id, nom, prenom, telFixe, telMobile, adresseMail, commentaire) values (
                    :id, :nom, :prenom, :telFixe, :telMobile, :adresseMail, :commentaire
                )";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":id", $spectateur->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":nom", $spectateur->getNom(), PDO::PARAM_STR);
            $stmt->bindValue(":prenom", $spectateur->getPrenom(), PDO::PARAM_STR);
            $stmt->bindValue(":telFixe", $spectateur->getTelFixe(), PDO::PARAM_STR);
            $stmt->bindValue(":telMobile", $spectateur->getTelMobile(), PDO::PARAM_STR);
            $stmt->bindValue(":adresseMail", $spectateur->getAdresseMail(), PDO::PARAM_STR);
            $stmt->bindValue(":commentaire", $spectateur->getCommentaire(), PDO::PARAM_STR);

            $ok = $stmt->execute();

            if($ok)
                $ok = $this->ajouterAdresse($spectateur);
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Spectator $spectateur
     * @return bool
     */
    private function ajouterAdresse($spectateur){
        $sql = "insert into adresse (id, rue, numero, localite, codePostal) values (:id, :rue, :numero, :localite, :codePostal)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":id", $spectateur->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":rue", $spectateur->getRue(), PDO::PARAM_STR);
            $stmt->bindValue(":numero", $spectateur->getNumero(), PDO::PARAM_STR);
            $stmt->bindValue(":localite", $spectateur->getLocalite(), PDO::PARAM_STR);
            $stmt->bindValue(":codePostal", $spectateur->getCodePostal(), PDO::PARAM_INT);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Reservation $reservation
     * @return bool
     */
    function ajouterReservation($reservation){
        $sql = "insert into reservations (id, nbSieges, remarque, SpecId, titre) values (:id, :nbSieges, :remarque, :SpecId, :titre)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":id", $reservation->getId(), PDO::PARAM_INT);
            $stmt->bindValue(":nbSieges", $reservation->getNbSieges(), PDO::PARAM_INT);
            $stmt->bindValue(":remarque", $reservation->getRemarque(), PDO::PARAM_STR);
            $stmt->bindValue(":SpecId", $reservation->getSpecId(), PDO::PARAM_INT);
            $stmt->bindValue(":titre", $reservation->getTitre(), PDO::PARAM_STR);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

    /**
     * @param Chaise $chaise
     * @return bool
     */
    function ajouterChaise($chaise){
        $sql = "insert into chaise (tri, date, heure, id, etat, ResId) values (:tri, :date, :heure, :id, :etat, :resId)";
        $stmt = null;
        $ok = false;

        try{
            $stmt = $this->db_archive->prepare($sql);

            $stmt->bindValue(":tri", $chaise->getTri(), PDO::PARAM_INT);
            $stmt->bindValue(":date", $chaise->getDate(), PDO::PARAM_STR);
            $stmt->bindValue(":heure", $chaise->getHeure(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $chaise->getId(), PDO::PARAM_STR);
            $stmt->bindValue(":etat", $chaise->getEtat(), PDO::PARAM_STR);
            $stmt->bindValue(":resId", $chaise->getResId(), PDO::PARAM_INT);

            $ok = $stmt->execute();
        }catch(PDOException $e){
            $ok = false;
        }finally{
            $stmt->closeCursor();
        }

        return $ok;
    }

}