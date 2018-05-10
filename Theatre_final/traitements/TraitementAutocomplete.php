<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\DBManager;
use app\manager\SpectatorManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_GET['term']) || !isset($_GET['action']) || empty($_GET['action'])){
    header("HTTP/1.1 400");
}
else{
    header("Content-type: application/JSON");

    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        $spectatorManager=new SpectatorManager($pdo);

        // autocomplétion du nom
        if($_GET['action']=='getNom'){
            $nom= $spectatorManager->autocompleteNom();
            // tableau de noms
            $tab=array();
            foreach ($nom as $elem){
                array_push($tab,$elem['nom']);
            }

            echo json_encode($tab);

        }else if($_GET['action']=='getPrenom'){ // autocomplétion prénom
            $nom= $spectatorManager->autocompletePrenom();
            // tableau prenoms
            $tab=array();
            foreach ($nom as $elem){
                array_push($tab,$elem['prenom']);
            }

            echo json_encode($tab);

        }

        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }


}