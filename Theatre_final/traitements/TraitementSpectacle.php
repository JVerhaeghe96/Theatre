<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\DBManager;
use app\manager\SpectacleManager;
use app\model\Spectacle;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["titre"]) || !isset($_POST["resume"]) || $_POST["titre"] == ''){
    header("Location: ../index.php?action=spectacle&error=nullError");
}else{
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // ajouter un spectacle
        $spectacleManager = new SpectacleManager($pdo);

        $titre = htmlspecialchars($_POST["titre"]);
        $resume = htmlspecialchars($_POST["resume"]);

        $spectacle = new Spectacle(array("titre" => $titre,"resume" => $resume));
        $success = $spectacleManager->ajouterSpectacle($spectacle);

        if($success){
            $dbManager->disconnect();
            header("Location: ../index.php?action=spectacle&success=true");
        }else{
            $dbManager->disconnect();
            header("Location: ../index.php?action=spectacle&error=titleExistError");
        }
    }else{
        header("Location: ../index.php?action=spectacle");
    }
}