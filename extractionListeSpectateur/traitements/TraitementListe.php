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


if(!isset($_GET['action'])){
    header("HTTP/1.1 403");
}else {
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    if($_GET["action"] == "reservation" && isset($_POST["titre"]) && isset($_POST["date"])
        && isset($_POST["numReservation"]) && isset($_POST["nom"]) && isset($_POST["prenom"])
        && $_POST["titre"] != "" && $_POST["date"] != ""){

        header("Content-type: application/JSON");
        $listeManager=new ListeManager($pdo);
        $liste=null;
        if(!empty($_POST['numReservation'])){
            $liste=$listeManager->getReservationByNumReservation($_POST['numReservation']);
        }else if(!empty($_POST['nom']) && !empty($_POST['prenom'])){
            $spectateurManager=new SpectatorManager($pdo);
            $spectateur = new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"]));

            $spectator = new Spectator($spectateurManager->getSpectatorByNomPrenom($spectateur));
            $liste=$listeManager->getReservationByNom($spectateur,explode('|',$_POST['date'])[0],explode('|',$_POST['date'])[1]);
        }else{
            header("HTTP/1.1 406");

        }
        echo json_encode($liste);

    }else
    if($_GET["action"]=="spectateur" && isset($_POST["nom"]) && isset($_POST["prenom"])){
        header("Content-type: application/JSON");

        $spectatorManager=new SpectatorManager($pdo);
        $spectateur = new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"]));

        echo json_encode(($spectatorManager->getSpectatorByNomPrenom($spectateur)));

    }else
    if($_GET["action"]=="listeSpectateur"){
        $spectatorManager = new SpectatorManager($pdo);
        $liste = $spectatorManager->getAllSpectators();
        echo json_encode($liste);
    }else
    if($_GET["action"] == "listeReservation" && isset($_POST["date"])){
        $listeManager=new ListeManager($pdo);
        $liste=$listeManager->getAllReservationsByDate(explode('|',$_POST['date'])[0],explode('|',$_POST['date'])[1]);
        echo json_encode($liste);
    }else
    if($_GET["action"]=="getDate"){
        $representationManager=new RepresentationManager($pdo);
        $tabDate=$representationManager->getAllRepresentationsByTitle($_POST["titre"]);
        echo json_encode($tabDate);

    }
}