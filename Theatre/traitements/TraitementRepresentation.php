<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\ChaiseManager;
use app\manager\DBManager;
use app\manager\RepresentationManager;
use app\model\Chaise;
use app\model\Representation;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["titre"]) || !isset($_POST["date"])||!isset($_POST["heure"]) || $_POST["titre"] == "" ||$_POST["date"] == ""||$_POST["heure"] == "" ){
    header("Location: ../index.php?error=nullError&action=representation");
}else {
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // début de la transaction
        $pdo->beginTransaction();

        //ajouter la représentation
        $representation=new Representation(array('date'=>$_POST["date"],'titre'=>$_POST["titre"],'heure'=>$_POST["heure"]));
        $representationManager=new RepresentationManager($pdo);
        $chaiseManager = new ChaiseManager($pdo);
        $ok=$representationManager->ajouterRepresentation($representation);

        // créer les chaises de la représentation
        for($lignes = 'A'; $lignes <= 'T'; $lignes++){
            for($colonnes = 1; $colonnes <= 20; $colonnes++){
                $id = $lignes. $colonnes;
                $chaise = new Chaise(array("date"=>$_POST["date"],'heure'=>$_POST["heure"], "id"=>$id));
                $ok = $chaiseManager->ajouterChaise($chaise);

                if(!$ok)
                    break;
            }
        }

        // transaction
        if($ok){
            $pdo->commit();
            $dbManager->disconnect();
            header("Location: ../index.php?action=representation&success=true"); // si la représentation à été ajoutée
        }
        else{
            $pdo->rollBack();
            $dbManager->disconnect();
            header("Location: ../index.php?action=representation&error=representationExistError"); // si la représentation à échouée
        }
    }else{
        header("Location: ../index.php?action=representation");
    }


}