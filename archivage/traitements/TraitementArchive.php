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

if(!isset($_POST["titre"]) && empty($_POST["titre"])){
    header("HTTP/1.1 406");
}else{
    $okPersonnel = true;
    $okPersonnage = true;

    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    $pdo_archive = $dbManager->connect_archive();

    $pdo->beginTransaction();
    $pdo_archive->beginTransaction();

    $spectacleManager = new SpectacleManager($pdo);
    $archiveManager = new ArchiveManager($pdo_archive);

    $donneesSpectacle = $spectacleManager->getAllDataBySpectacle($_POST["titre"]);
    $personnelSpectacle = $spectacleManager->getSpectacleInformationsByTitle($_POST["titre"]);

    $spectacle = new Spectacle(array("titre" => $personnelSpectacle[0]["titre"], "resume" => $personnelSpectacle[0]["resume"]));

    $okSpectacle = $archiveManager->ajouterSpectacle($spectacle);

    if($okSpectacle){
        foreach($personnelSpectacle as $pelSpectacle){

            $personnel = new Personnel(array("id" => $pelSpectacle["pelId"], "nom" => $pelSpectacle["pelNom"], "prenom" => $pelSpectacle["pelPrenom"],
                "fonction" => $pelSpectacle["fonction"]));

            $okPersonnel = $archiveManager->ajouterPersonnel($personnel, $spectacle->getTitre());

            if($okPersonnel && $personnel->getFonction() == "acteur"){
                $personnages = new Personnage(array("titre" => $pelSpectacle["titre"], "nom" => $pelSpectacle["pegNom"],
                    "prenom" => $pelSpectacle["pegPrenom"], "id" => $pelSpectacle["pelId"]));

                $okPersonnage = $archiveManager->ajouterPersonnages($personnages);
            }
        }
    }


    if($okSpectacle && $okPersonnel && $okPersonnage){
        $pdo_archive->commit();

        /*
         *
         * traitement de la suppression des données dans la base theatre
         *
         */

        $pdo->commit();
    }
    else
        $pdo_archive->rollBack();
}