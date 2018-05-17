<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\ChaiseManager;
use app\manager\DBManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

header("Content-type: text/plain");
if(isset($_GET["action"])){
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        $chaiseManager = new ChaiseManager($pdo);
        if($_GET["action"] == "copier"){ // copier le plan de la salle vers une autre date
            if(isset($_POST["dateCopie"]) && isset($_POST["currentDate"])){ // vérification de la date de copie et la date à copier
                $currentDate = explode('|', $_POST["currentDate"])[0];
                $currentHeure = explode('|', $_POST["currentDate"])[1];
                $dateCopie = explode('|', $_POST["dateCopie"])[0];
                $heureCopie = explode('|', $_POST["dateCopie"])[1];

                // début de la transaction
                $pdo->beginTransaction();

                // supprimer toutes les chaises de la date à choisir
                $ok1 = $chaiseManager->deleteAllByDate($dateCopie, $heureCopie);
                $chaises = $chaiseManager->getAllByDates($currentDate, $currentHeure);
                $ok2 = false;

                // copie les chaises une à une
                foreach ($chaises as $chaise){
                    $etat = $chaise->getEtat();
                    $etat = $etat == 'D' || $etat == 'N' ? $etat : 'D';
                    $chaise->setEtat($etat);
                    $chaise->setDate($dateCopie);
                    $chaise->setHeure($heureCopie);

                    $ok2 = $chaiseManager->copierPlanSalle($chaise);
                    if(!$ok2)
                        break;
                }

                if($ok1 && $ok2){
                    $pdo->commit();
                }else{
                    $pdo->rollBack();
                    header("HTTP/1.1 400");
                }
            }
        }else if($_GET["action"] == "changerEtat"){
            if(isset($_POST["id"]) && isset($_POST["date"])){
                $chaise = $chaiseManager->getByIdAndDate($_POST["id"], explode("|",$_POST["date"])[0],explode("|",$_POST["date"])[1]);
                if($chaise->getEtat() == 'O' || $chaise->getEtat() == 'R'){
                    header("HTTP/1.1 400");
                }else{
                    if($chaise->getEtat() == 'D')
                        $chaise->setEtat("N");
                    else
                        $chaise->setEtat("D");

                    $ok = $chaiseManager->modifierEtat($chaise);

                    if(!$ok)
                        header("HTTP/1.1 400");
                }
            }
        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }


}