<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\ArchiveManager;
use app\manager\DBManager;
use app\manager\SpectacleManager;
use app\model\Chaise;
use app\model\Personnage;
use app\model\Personnel;
use app\model\Representation;
use app\model\Reservation;
use app\model\Spectacle;
use app\model\Spectator;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["titre"]) && empty($_POST["titre"])){
    header("HTTP/1.1 406");
}else{
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    $pdo_archive = $dbManager->connect_archive();

    if($pdo != null || $pdo_archive != null){
        // initialisation des variables
        $okPersonnel = true;
        $okPersonnage = true;
        $okRepresentation = true;
        $okSpectateur = true;
        $okReservation = true;
        $okChaise = true;

        $spectateurPrec = array();
        $reservationPrec = array();
        $tabIdPersonnel = array();
        $tabRepresentation = array();

        $spectacleManager = new SpectacleManager($pdo);
        $archiveManager = new ArchiveManager($pdo_archive);

        // début de la transaction
        $pdo->beginTransaction();
        $pdo_archive->beginTransaction();

        // récupération de toutes les données relatives à un spectacle
        $donneesSpectacle = $spectacleManager->getAllDataBySpectacle($_POST["titre"]);
        $personnelSpectacle = $spectacleManager->getSpectacleInformationsByTitle($_POST["titre"]);

        // ajout du spectacle dans la base d'archive
        $spectacle = new Spectacle(array("titre" => $personnelSpectacle[0]["titre"], "resume" => $personnelSpectacle[0]["resume"]));
        $okSpectacle = $archiveManager->ajouterSpectacle($spectacle);

        if($okSpectacle){
            // ajouter tout le personnel et les personnages du spectacle dans la base archive
            foreach($personnelSpectacle as $pelSpectacle){
                $personnel = new Personnel(array("id" => $pelSpectacle["pelId"], "nom" => $pelSpectacle["pelNom"], "prenom" => $pelSpectacle["pelPrenom"],
                    "fonction" => $pelSpectacle["fonction"]));

                // ajout du personnel dans la base archive
                $okPersonnel = $archiveManager->ajouterPersonnel($personnel, $spectacle->getTitre());
                array_push($tabIdPersonnel, $personnel->getId());

                if($okPersonnel && $personnel->getFonction() == "acteur"){
                    $personnages = new Personnage(array("titre" => $pelSpectacle["titre"], "nom" => $pelSpectacle["pegNom"],
                        "prenom" => $pelSpectacle["pegPrenom"], "id" => $pelSpectacle["pelId"]));
                    // ajout du personnage dans la base archive
                    $okPersonnage = $archiveManager->ajouterPersonnages($personnages);
                }

                if(!$okPersonnel || !$okPersonnage)
                    break;
            }
            // traitement de la première ligne du tableau donneesSpectacle
            $donneesPrec = $donneesSpectacle[0];
            $spectateur = new Spectator(array("id" => $donneesPrec["specId"], "nom" => $donneesPrec["specNom"],
                    "prenom" => $donneesPrec["specPrenom"], "telFixe" => $donneesPrec["telFixe"], "telMobile" => $donneesPrec["telMobile"],
                    "adresseMail" => $donneesPrec["adresseMail"], "commentaire" => $donneesPrec["commentaire"],
                    "rue" => $donneesPrec["rue"], "numero" => $donneesPrec["numero"], "localite" => $donneesPrec["localite"],
                    "codePostal" => $donneesPrec["codePostal"]));

            $representationPrec = new Representation(array("date" => $donneesPrec["date"], "heure" => $donneesPrec["heure"], "titre" => $donneesPrec["titre"]));

            $chaisePrec = new Chaise(array("id" => $donneesPrec["chaiseId"], "tri" => $donneesPrec["chaiseTri"], "date" => $donneesPrec["date"],
                    "heure" => $donneesPrec["heure"], "etat" => $donneesPrec["etat"], "ResId" => $donneesPrec["resId"]));

            $reservation = new Reservation(array("id" => $donneesPrec["resId"], "nbSieges" => $donneesPrec["nbSieges"],
                    "remarque" => $donneesPrec["remarque"], "SpecId" => $donneesPrec["specId"], "titre" => $donneesPrec["titre"]));

            // ajout de la représentation dans la base archive
            $okRepresentation = $archiveManager->ajouterRepresentation($representationPrec);
            array_push($tabRepresentation, $representationPrec);

            if($spectateur->getId() != null){
                // ajout du spectateur et de sa réservation dans la base archive
                $archiveManager->ajouterSpectateur($spectateur);
                $okReservation = $archiveManager->ajouterReservation($reservation);
            }
            // ajout de la chaise dans la base archive
            $okChaise = $archiveManager->ajouterChaise($chaisePrec);

            array_push($spectateurPrec, $spectateur);
            array_push($reservationPrec, $reservation);

            if($okRepresentation && $okReservation && $okChaise){
                // ajouter les représentations, chaises, réservations et spectateurs à la base archive
                for($i = 1; $i < sizeof($donneesSpectacle); $i++){
                    $donnees = $donneesSpectacle[$i];
                    $spectateur = new Spectator(array("id" => $donnees["specId"], "nom" => $donnees["specNom"],
                        "prenom" => $donnees["specPrenom"], "telFixe" => $donnees["telFixe"], "telMobile" => $donnees["telMobile"],
                        "adresseMail" => $donnees["adresseMail"], "commentaire" => $donnees["commentaire"],
                        "rue" => $donnees["rue"], "numero" => $donnees["numero"], "localite" => $donnees["localite"],
                        "codePostal" => $donnees["codePostal"]));

                    $representation = new Representation(array("date" => $donnees["date"], "heure" => $donnees["heure"], "titre" => $donnees["titre"]));

                    $chaise = new Chaise(array("id" => $donnees["chaiseId"], "tri" => $donnees["chaiseTri"], "date" => $donnees["date"],
                        "heure" => $donnees["heure"], "etat" => $donnees["etat"], "ResId" => $donnees["resId"]));

                    $reservation = new Reservation(array("id" => $donnees["resId"], "nbSieges" => $donnees["nbSieges"],
                        "remarque" => $donnees["remarque"], "SpecId" => $donnees["specId"], "titre" => $donnees["titre"]));


                    if($representationPrec->getDate() != $representation->getDate() || $representationPrec->getHeure() != $representation->getHeure()){
                        // ajout de la représentation dans la base archive
                        $okRepresentation = $archiveManager->ajouterRepresentation($representation);
                        $representationPrec = $representation;
                        array_push($tabRepresentation, $representation);
                    }

                    if($spectateur->getId() != null && !containsSpectator($spectateurPrec, $spectateur)){
                        // ajout du spectateur dans la base archive
                        $archiveManager->ajouterSpectateur($spectateur);
                        array_push($spectateurPrec, $spectateur);
                    }

                    if($reservation->getId() != null && !containsReservation($reservationPrec, $reservation)){
                        // ajout de la réservation dans la base archive
                        $okReservation = $archiveManager->ajouterReservation($reservation);
                        array_push($reservationPrec, $reservation);
                    }

                    // ajout de la chaise dans la base archive
                    $okChaise = $archiveManager->ajouterChaise($chaise);

                    if(!$okRepresentation || !$okSpectateur || !$okReservation || !$okChaise)
                        break;
                }
            }
        }


        //  quand TOUS les insert sont terminés
        if($okSpectacle && $okPersonnel && $okPersonnage && $okRepresentation && $okReservation && $okChaise){

            $okDeletePersonnel = true;
            // suppression de tout le personnel dans la DB
            foreach($tabIdPersonnel as $id){
                $okDeletePersonnel = $spectacleManager->deletePersonnelById($id);
                if(!$okDeletePersonnel)
                    break;
            }

            $okDeleteChaise = true;
            // suppression de toutes les chaises dans la DB
            foreach($tabRepresentation as $representation){
                $okDeleteChaise = $spectacleManager->deleteChaiseByRepresentation($representation);
            }

            // suppression de toutes les représentations dans la DB
            $okDeleteRepresentation = $spectacleManager->deleteRepresentationBySpectacle($spectacle->getTitre());

            // suppression de toutes les réservations dans la DB
            $okDeleteReservation = $spectacleManager->deleteReservationBySpectacle($spectacle->getTitre());

            // suppression du spectacle
            $okDeleteSpectacle = $spectacleManager->deleteSpectacle($spectacle->getTitre());

            // transaction
            if($okDeletePersonnel && $okDeleteChaise && $okDeleteRepresentation && $okDeleteReservation && $okDeleteSpectacle){
                $pdo_archive->commit();
                $pdo->commit();
                $dbManager->disconnect();
                $dbManager->disconnect_archive();
            }else{
                $pdo_archive->rollBack();
                $pdo->rollBack();
                $dbManager->disconnect();
                $dbManager->disconnect_archive();
                header("HTTP/1.1 406");
            }

        }else{
            $pdo_archive->rollBack();
            $pdo->rollBack();
            $dbManager->disconnect();
            $dbManager->disconnect_archive();
            header("HTTP/1.1 403");
        }
    }else{
        header("HTTP/1.1 500");
    }

}

/**
 * @param array $tab
 * @param Spectator $elem
 * @return boolean
 */
function containsSpectator($tab, $elem){

    foreach ($tab as $spectateur){
        if($spectateur->getId() == $elem->getId())
            return true;
    }

    return false;
}

/**
 * @param array $tab
 * @param Reservation $elem
 * @return boolean
 */
function containsReservation($tab, $elem){

    foreach ($tab as $reservation){
        if($reservation->getId() == $elem->getId())
            return true;
    }

    return false;
}