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

    /**
     * @param String $titre
     * @return array
     */
    function getSpectacleInformationsByTitle($titre){
        $sql = 'select sp.titre, sp.resume, pel.id as pelId, pel.nom as pelNom, pel.prenom as pelPrenom, pel.fonction, peg.nom as pegNom, peg.prenom as pegPrenom
                from spectacle as sp
                inner join organiser as org on org.titre = sp.titre
                inner join personnel as pel on pel.id = org.id
                left join personnages as peg on peg.id = pel.id
                left join personnages on peg.titre = sp.titre
                where sp.titre = :titre
                group by pel.id';
        $stmt = null;
        $result = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $result = array();
        }finally{
            $stmt->closeCursor();
        }


        return $result;
    }

    /**
     * @param String $titre
     * @return array
     */
    function getAllDataBySpectacle($titre){
        $sql = "select sp.titre, rep.date, rep.heure, c.tri as chaiseTri, c.id as chaiseId, c.etat, res.id, res.nbSieges, res.remarque, spec.id as specId, spec.nom as specNom, spec.prenom as specPrenom, spec.telFixe, spec.telMobile, spec.adresseMail, spec.commentaire, ad.rue, ad.numero, ad.localite, ad.codePostal
                from spectacle as sp
                inner join representation as rep on rep.titre = sp.titre
                inner join chaise as c on c.date = rep.date
                inner join chaise on c.heure = rep.heure
                inner join reservations as res on res.id = c.ResId
                inner join spectateurs as spec on spec.id = res.SpecId
                inner join adresse as ad on ad.id = spec.id
                where sp.titre = :titre
                group by c.id, c.date, c.heure
                order by c.tri";
        $stmt = null;
        $result = array();

        try{
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            $result = array();
        }finally{
            $stmt->closeCursor();
        }

        return $result;
    }
}