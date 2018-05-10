<?php

require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/SpectacleManager.php";
require_once "../model/PO.php";
require_once "../model/Spectacle.php";

if(!isset($_POST["titre"]) || !isset($_POST["resume"]) || $_POST["titre"] == '' || $_POST["resume"] == ''){
    header("Location: ../index.php?action=spectacle&error=nullError");
}else{
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $spectacleManager = new SpectacleManager($pdo);

    $spectacle = new Spectacle(array("titre" => $_POST["titre"],"resume" => $_POST["resume"]));

    $success = $spectacleManager->ajouterSpectacle($spectacle);

    if($success){
        header("Location: ../index.php?action=spectacle&success=true");
    }else{
        header("Location: ../index.php?action=spectacle&error=titleExistError");
    }
}