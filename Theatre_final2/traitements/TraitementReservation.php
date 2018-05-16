<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\ChaiseManager;
use app\manager\DBManager;
use app\manager\RepresentationManager;
use app\manager\ReservationManager;
use app\manager\SpectatorManager;
use app\model\Chaise;
use app\model\Reservation;
use app\model\Spectator;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(isset($_GET["action"])){
    //  connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        if($_GET["action"]=="getTitre"){
            header("Content-type: application/JSON");

            if(isset($_POST["titre"])){
                // récupérer toutes les dates pour un spectacle
                $representationManager=new RepresentationManager($pdo);
                echo json_encode($representationManager->getAllDatesByTitle($_POST["titre"]));
            }
        }if($_GET["action"]=="reservation"){ // reservation
            header("Content-type: text/plain");
            if(isset($_POST["titre"]) && isset($_POST["nbPlaces"]) && isset($_POST["commentaire"]) && isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["tabPlaces"]) && $_POST["nbPlaces"]!=""){

                $chaiseManager=new ChaiseManager($pdo);
                $reservationManager=new ReservationManager($pdo);
                $spectateurManager=new SpectatorManager($pdo);

                $nom = htmlspecialchars($_POST["nom"]);
                $prenom = htmlspecialchars($_POST["prenom"]);

                $spectateur = $spectateurManager->getSpectatorByNomPrenom(new Spectator(array("nom" => $nom, "prenom" => $prenom)));

                $commentaire = htmlspecialchars($_POST["commentaire"]);

                $reservation=new Reservation(array('nbSieges'=>$_POST["nbPlaces"],'remarque'=>$commentaire,'SpecId'=>$spectateur["id"],'titre'=>$_POST["titre"]));

                // début de la transaction
                $pdo->beginTransaction();

                // ajouter une réservation
                $ok1=$reservationManager->ajouterReservation($reservation);
                $id=$pdo->lastInsertId(); // récupérer l'id du dernier élément inséré dans la DB

                if($ok1){
                    //changer l'état d'une chaise
                    foreach($_POST["tabPlaces"] as $places){
                        $chaise=new Chaise($places);
                        $chaise->setResId($id);
                        $ok2=$chaiseManager->reserverChaise($chaise);
                    }

                }
                if($ok1 && $ok2){
                    $pdo->commit();
                    echo "reservation réussie";
                }
                else{
                    $pdo->rollBack();
                    header("HTTP/1.1 401");
                }
            }else{
                header("HTTP/1.1 403");
            }
        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }
}