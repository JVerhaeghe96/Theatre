<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../model/PO.php";
require_once "../manager/SpectatorManager.php";
require_once "../model/Spectator.php";

if(isset($_GET["action"])){
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $spectatorManager = new SpectatorManager($pdo);

    if($_GET["action"]=="getId"){
        //rechercher un spectateur par son id
        header("Content-type: application/JSON");
        if(isset($_POST["nom"])){
            $spectateur=$spectatorManager->getSpectatorByNomPrenom(new Spectator(array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"])));
            echo json_encode($spectateur);

        }else{
            header('HTTP1.1 403');
        }
    }else if($_GET["action"]=="modifier") {
        if (!isset($_POST["nom"]) || !isset($_POST["adresse"]) || !isset($_POST["numero"])
            || !isset($_POST["localite"]) || !isset($_POST["cPostal"]) || !isset($_POST["noFixe"]) || !isset($_POST["noGsm"])
            || !isset($_POST["mail"]) || !isset($_POST["commentaire"]) || $_POST["nom"] == "" || $_POST["adresse"] == ""
            || $_POST["numero"] == "" || $_POST["localite"] == "" || $_POST["cPostal"] == "") {

            header("Location: ../index.php?error=nullError&action=spectateur");
        } else {
            $pdo->beginTransaction();

            //modifier un spectateur
            $spectator = new Spectator(array('id' => $_POST["nom"], 'rue' => $_POST["adresse"], 'numero' => $_POST["numero"],
                'localite' => $_POST["localite"], 'codePostal' => $_POST["cPostal"], 'telFixe' => $_POST["noFixe"], 'telMobile' => $_POST["noGsm"], 'adresseMail' => $_POST["mail"],
                'commentaire' => $_POST["commentaire"]));


            $ok1 = $spectatorManager->modifierSpectatorById($spectator);
            $ok2 = $spectatorManager->modifierAdresseById($spectator);

            if ($ok1 && $ok2) {
                $pdo->commit();
                header("Location: ../index.php?action=spectateur&success=true"); // si le spectateur à été modifié

            } else {
                $pdo->rollBack();
                header("Location: ../index.php?action=spectateur&error=spectateuModifierrExistError"); // si la modification du spectateur à échouée

            }
        }
    }
}