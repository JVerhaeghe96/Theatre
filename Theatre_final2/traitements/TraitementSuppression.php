<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\ChaiseManager;
use app\manager\DBManager;
use app\manager\ReservationManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["resId"]) || empty($_POST["resId"])){
    header("HTTP/1.1 406");
}else{
    $okChaises = false;

    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        $reservationManager = new ReservationManager($pdo);
        $chaiseManager = new ChaiseManager($pdo);

        // debut de la transaction
        $pdo->beginTransaction();
        $chaises = $chaiseManager->getByResId($_POST["resId"]);

        // remmettre à l'état Disponible et l'id de réservation à null des chaises réservées
        foreach($chaises as $chaise){
            $chaise->setResId(null);
            $chaise->setEtat("D");
            $okChaises = $chaiseManager->reserverChaise($chaise);
        }

        // suppresion de la réservation
        $okSuppression = $reservationManager->deleteReservationById($_POST["resId"]);

        // transaction
        if($okChaises && $okSuppression){
            $pdo->commit();
        }else{
            $pdo->rollBack();
            header("HTTP/1.1 403");
        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }


}