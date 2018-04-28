<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/RepresentationManager.php";
require_once "../manager/ReservationManager.php";
require_once "../manager/ChaiseManager.php";
require_once "../model/Representation.php";
require_once "../model/Reservation.php";
require_once "../model/Chaise.php";
require_once "../utils/DateUtils.php";
if(isset($_GET["action"])){
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    if($_GET["action"]=="getTitre"){
        header("Content-type: application/JSON");

        if(isset($_POST["titre"])){

            $representationManager=new RepresentationManager($pdo);
            echo json_encode($representationManager->getAllDatesByTitle($_POST["titre"]));

        }
    }if($_GET["action"]=="reservation"){
        header("Content-type: text/plain");
        if(isset($_POST["titre"]) && isset($_POST["nbPlaces"]) && isset($_POST["commentaire"]) && isset($_POST["nom"]) && isset($_POST["tabPlaces"]) && $_POST["nbPlaces"]!=""){

            $chaiseManager=new ChaiseManager($pdo);
            $reservationManager=new ReservationManager($pdo);
            $reservation=new Reservation(array('nbSieges'=>$_POST["nbPlaces"],'remarque'=>$_POST["commentaire"],'SpecId'=>$_POST["nom"],'titre'=>$_POST["titre"]));

            $pdo->beginTransaction();
            $ok1=$reservationManager->ajouterReservation($reservation);
            $id=$pdo->lastInsertId();

            if($ok1){
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
}