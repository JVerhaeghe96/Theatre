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

}