<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 27-04-18
 * Time: 22:32
 */

require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/ArchiveManager.php";
require_once "../manager/SpectacleManager.php";

require_once "../model/Spectacle.php";
require_once "../model/Personnel.php";
require_once "../model/Personnage.php";
require_once "../model/Representation.php";
require_once "../model/Chaise.php";
require_once "../model/Reservation.php";
require_once "../model/Spectator.php";

if(!isset($_POST["titre"]) && empty($_POST["titre"])){
    header("HTTP/1.1 406");
}else{

    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    $pdo_archive = $dbManager->connect_archive();

    if($pdo != null || $pdo_archive != null){
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

        $pdo->beginTransaction();
        $pdo_archive->beginTransaction();


        $donneesSpectacle = $spectacleManager->getAllDataBySpectacle($_POST["titre"]);
        $personnelSpectacle = $spectacleManager->getSpectacleInformationsByTitle($_POST["titre"]);

        $spectacle = new Spectacle(array("titre" => $personnelSpectacle[0]["titre"], "resume" => $personnelSpectacle[0]["resume"]));

        $okSpectacle = $archiveManager->ajouterSpectacle($spectacle);

        if($okSpectacle){
            foreach($personnelSpectacle as $pelSpectacle){

                $personnel = new Personnel(array("id" => $pelSpectacle["pelId"], "nom" => $pelSpectacle["pelNom"], "prenom" => $pelSpectacle["pelPrenom"],
                    "fonction" => $pelSpectacle["fonction"]));

                $okPersonnel = $archiveManager->ajouterPersonnel($personnel, $spectacle->getTitre());
                array_push($tabIdPersonnel, $personnel->getId());

                if($okPersonnel && $personnel->getFonction() == "acteur"){
                    $personnages = new Personnage(array("titre" => $pelSpectacle["titre"], "nom" => $pelSpectacle["pegNom"],
                        "prenom" => $pelSpectacle["pegPrenom"], "id" => $pelSpectacle["pelId"]));

                    $okPersonnage = $archiveManager->ajouterPersonnages($personnages);
                }

                if(!$okPersonnel || !$okPersonnage)
                    break;
            }
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

            $okRepresentation = $archiveManager->ajouterRepresentation($representationPrec);
            array_push($tabRepresentation, $representationPrec);

            if($spectateur->getId() != null){
                $okSpectateur = $archiveManager->ajouterSpectateur($spectateur);
                $okReservation = $archiveManager->ajouterReservation($reservation);
            }
            $okChaise = $archiveManager->ajouterChaise($chaisePrec);

            array_push($spectateurPrec, $spectateur);
            array_push($reservationPrec, $reservation);

            if($okRepresentation && $okSpectateur && $okReservation && $okChaise){
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
                        $okRepresentation = $archiveManager->ajouterRepresentation($representation);
                        $representationPrec = $representation;
                        array_push($tabRepresentation, $representation);
                    }

                    if($spectateur->getId() != null && !containsSpectator($spectateurPrec, $spectateur)){
                        $okSpectateur = $archiveManager->ajouterSpectateur($spectateur);
                        array_push($spectateurPrec, $spectateur);
                    }

                    if($reservation->getId() != null && !containsReservation($reservationPrec, $reservation)){
                        $okReservation = $archiveManager->ajouterReservation($reservation);
                        array_push($reservationPrec, $reservation);
                    }

                    $okChaise = $archiveManager->ajouterChaise($chaise);

                    if(!$okRepresentation || !$okSpectateur || !$okReservation || !$okChaise)
                        break;
                }
            }
        }


        //  quand TOUS les insert sont terminés
        if($okSpectacle && $okPersonnel && $okPersonnage && $okRepresentation && $okSpectateur && $okReservation && $okChaise){

            $okDeletePersonnel = true;
            foreach($tabIdPersonnel as $id){
                $okDeletePersonnel = $spectacleManager->deletePersonnelById($id);
                if(!$okDeletePersonnel)
                    break;
            }

            $okDeleteChaise = true;
            foreach($tabRepresentation as $representation){
                $okDeleteChaise = $spectacleManager->deleteChaiseByRepresentation($representation);
            }

            $okDeleteRepresentation = $spectacleManager->deleteRepresentationBySpectacle($spectacle->getTitre());

            $okDeleteReservation = $spectacleManager->deleteReservationBySpectacle($spectacle->getTitre());

            $okDeleteSpectacle = $spectacleManager->deleteSpectacle($spectacle->getTitre());

            if($okDeletePersonnel && $okDeleteChaise && $okDeleteRepresentation && $okDeleteReservation && $okDeleteSpectacle){
                $pdo_archive->commit();
                $pdo->commit();
            }else{
                $pdo_archive->rollBack();
                $pdo->rollBack();
                header("HTTP/1.1 403");
            }

        }else{
            $pdo_archive->rollBack();
            $pdo->rollBack();
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