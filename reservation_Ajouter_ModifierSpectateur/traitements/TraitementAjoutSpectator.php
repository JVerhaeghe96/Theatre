<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/SpectatorManager.php";
require_once "../model/PO.php";
require_once "../model/Spectator.php";

if(!isset($_POST["nom"]) || !isset($_POST["prenom"]) || !isset($_POST["adresse"]) || !isset($_POST["numero"])
    || !isset($_POST["localite"]) || !isset($_POST["cPostal"]) || !isset($_POST["noFixe"]) || !isset($_POST["noGsm"])
    || !isset($_POST["mail"]) || !isset($_POST["commentaire"]) || $_POST["nom"] == "" ||$_POST["prenom"] == "" ||$_POST["adresse"] == ""
    ||$_POST["numero"] == "" ||$_POST["localite"] == "" ||$_POST["cPostal"] == "" ||$_POST["noFixe"] == "" ||$_POST["noGsm"] == ""
    ||$_POST["mail"] == "" ||$_POST["commentaire"] == ""){

    header("Location: ../index.php?error=nullError&action=reservation");
}
else {
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $pdo->beginTransaction();


    //ajouter un spectateur dans la BD
    $spectator = new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"], 'rue' => $_POST["adresse"],'numero' => $_POST["numero"],
        'localite' => $_POST["localite"],'codePostal' => $_POST["cPostal"],'telFixe' => $_POST["noFixe"],'telMobile' => $_POST["noGsm"],'adresseMail' => $_POST["mail"],
        'commentaire' => $_POST["commentaire"] ));
    $spectatorManager = new SpectatorManager($pdo);
    $ok1 = $spectatorManager->ajouterSpectator($spectator);
    $ok2=$spectatorManager->ajouterAdresse($spectator);

    if ($ok1 && $ok2){
        $pdo->commit();
        header("Location: ../index.php?action=reservation&success=true"); // si le spectateur à été ajoutée

    }

    else {
        $pdo->rollBack();
        header("Location: ../index.php?action=reservation&error=spectateurExistError"); // si l'ajout du spectateur à échoué

    }

}