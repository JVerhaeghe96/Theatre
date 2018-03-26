<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/RepresentationManager.php";
require_once "../manager/ChaiseManager.php";
require_once "../model/PO.php";
require_once "../model/Representation.php";
require_once "../model/Chaise.php";
require_once "../utils/DateUtils.php";

if(!isset($_POST["titre"]) || !isset($_POST["date"]) || $_POST["titre"] == "" ||$_POST["date"] == ""){
    header("Location: ../index.php?error=nullError&action=representation");
}else {
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $pdo->beginTransaction();

    //ajouter la représentation dans la BD
    $representation=new Representation(array('date'=>$_POST["date"],'titre'=>$_POST["titre"]));
    $representationManager=new RepresentationManager($pdo);
    $chaiseManager = new ChaiseManager($pdo);
    $ok=$representationManager->ajouterRepresentation($representation);

    for($lignes = 'A'; $lignes <= 'J'; $lignes++){
        for($colonnes = 1; $colonnes <= 14; $colonnes++){
            $id = $lignes. $colonnes;
            $chaise = new Chaise(array("date"=>$_POST["date"], "id"=>$id));
            $ok = $chaiseManager->ajouterChaise($chaise);
        }
    }

    if($ok){
        $pdo->commit();
        header("Location: ../index.php?action=representation&success=true"); // si la représentation à été ajoutée
    }
    else{
        $pdo->rollBack();
        header("Location: ../index.php?action=representation&error=representationExistError"); // si la représentation à échouée
    }
}