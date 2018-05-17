<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\DBManager;
use app\manager\ListeManager;
use app\manager\RepresentationManager;
use app\manager\SpectatorManager;
use app\model\Spectator;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_GET['action'])){
    header("HTTP/1.1 403");
}else {
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // rechercher une réservations
        if($_GET["action"] == "reservation" && isset($_POST["titre"]) && isset($_POST["date"])
            && isset($_POST["numReservation"]) && isset($_POST["nom"]) && isset($_POST["prenom"])
            && $_POST["titre"] != "" && $_POST["date"] != ""){

            header("Content-type: application/JSON");
            $listeManager=new ListeManager($pdo);
            $liste=null;

            // si le numéro de réservation a été précisé
            if(!empty($_POST['numReservation'])){
                $liste=$listeManager->getReservationByNumReservation($_POST['numReservation']);
            }else if(!empty($_POST['nom']) && !empty($_POST['prenom'])){ // si le numéro de réservation n'a pas été précisé
                // récupérer la réservation
                $spectateur = new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"]));
                $liste=$listeManager->getReservationByNom($spectateur,explode('|',$_POST['date'])[0],explode('|',$_POST['date'])[1]);
            }else{
                header("HTTP/1.1 406");
            }
            echo json_encode($liste);

        }else
            // rechercher un spectateur
            if($_GET["action"]=="spectateur" && isset($_POST["nom"]) && isset($_POST["prenom"])){
                header("Content-type: application/JSON");

                // récupérer le spectateur
                $spectatorManager=new SpectatorManager($pdo);
                $spectateur = new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"]));
                echo json_encode(($spectatorManager->getSpectatorByNomPrenom($spectateur)));

            }else
                // liste des spectateurs
                if($_GET["action"]=="listeSpectateur"){
                    // récupérer tous les spectateurs
                    $spectatorManager = new SpectatorManager($pdo);
                    $liste = $spectatorManager->getAllSpectators();
                    echo json_encode($liste);
                }else
                    // liste des réservations
                    if($_GET["action"] == "listeReservation" && isset($_POST["date"])){
                        // récupérer toutes les réservations pour une date de représentation
                        $listeManager=new ListeManager($pdo);
                        $liste=$listeManager->getAllReservationsByDate(explode('|',$_POST['date'])[0],explode('|',$_POST['date'])[1]);
                        echo json_encode($liste);
                    }else
                        if($_GET["action"]=="getDate"){
                            // récupérer touts les dates de représentations d'un spectacle
                            $representationManager=new RepresentationManager($pdo);
                            $tabDate=$representationManager->getAllRepresentationsByTitle($_POST["titre"]);
                            echo json_encode($tabDate);

                        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }
}