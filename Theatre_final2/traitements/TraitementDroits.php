<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\AdminManager;
use app\manager\DBManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

header("Content-type: text/plain");
if(isset($_POST["user"])&& isset($_POST["droit"]) && isset($_POST["valeur"])){

    //  connexion  DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // modifier les droits
        $connexionManager = new AdminManager($pdo);
        $connexionManager->modifierDroit($_POST["user"], $_POST["droit"], $_POST["valeur"]);

        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }
}

