<?php
require_once'../manager/AbstractManager.php';
require_once '../manager/DBManager.php';
require_once '../manager/SpectatorManager.php';

if(!isset($_GET['term']) || !isset($_GET['action']) || empty($_GET['action'])){
    header("HTTP/1.1 400");
}
else{
    header("Content-type: application/JSON");
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    $spectatorManager=new SpectatorManager($pdo);

    if($_GET['action']=='getNom'){
        $nom= $spectatorManager->autocompleteNom();
        $tab=array();
        foreach ($nom as $elem){
            array_push($tab,$elem['nom']);
        }

        echo json_encode($tab);

    }else if($_GET['action']=='getPrenom'){
        //  On obtient une connexion à la DB
        $nom= $spectatorManager->autocompletePrenom();
        $tab=array();
        foreach ($nom as $elem){
            array_push($tab,$elem['prenom']);
        }

        echo json_encode($tab);

    }
}