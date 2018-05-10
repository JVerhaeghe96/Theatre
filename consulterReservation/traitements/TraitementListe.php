<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/RepresentationManager.php";
require_once "../manager/ReservationManager.php";
require_once "../manager/ChaiseManager.php";
require_once "../manager/ListeManager.php";
require_once "../manager/SpectatorManager.php";
require_once "../model/PO.php";
require_once "../model/Representation.php";
require_once "../model/Spectator.php";
require_once "../model/Reservation.php";
require_once "../model/Chaise.php";
require_once "../utils/DateUtils.php";

if(!isset($_POST["titre"]) || !isset($_POST["date"])||!isset($_POST["nom"])|| !isset($_POST["numReservation"])|| $_POST["titre"] == "" ||$_POST["date"] == ""){
    header("HTTP/1.1 400");
}else {
    header("Content-type: application/JSON");
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $listeManager=new ListeManager($pdo);
    $liste=null;
    if(!empty($_POST['numReservation'])){
        $liste=$listeManager->getReservationByNumReservation($_POST['numReservation']);
    }else if(!empty($_POST['nom'])){
        $spectateurManager=new SpectatorManager($pdo);
        $spectateur=new Spectator($spectateurManager->getSpectatorById($_POST['nom']));
        $liste=$listeManager->getReservationByNom($spectateur,explode('|',$_POST['date'])[0],explode('|',$_POST['date'])[1]);
    }else{
        header("HTTP/1.1 400");
    }

    echo json_encode($liste);





}