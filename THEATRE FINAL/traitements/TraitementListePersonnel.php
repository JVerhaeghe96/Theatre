<?php
namespace app\traitements;
use app\Autoloader;
use app\manager\DBManager;
use app\manager\PersonnelManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["titre"])|| empty($_POST["titre"])){
    header("HTTP/1.1 403");
}else{
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        $personnelManager=new PersonnelManager($pdo);
        $liste=$personnelManager->listerPersonnel($_POST["titre"]);
        echo json_encode($liste);
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }
}