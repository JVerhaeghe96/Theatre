<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\DBManager;
use app\manager\SpectatorManager;
use app\model\Spectator;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["nom"]) || !isset($_POST["prenom"]) || !isset($_POST["adresse"]) || !isset($_POST["numero"])
    || !isset($_POST["localite"]) || !isset($_POST["cPostal"]) || !isset($_POST["noFixe"]) || !isset($_POST["noGsm"])
    || !isset($_POST["mail"]) || !isset($_POST["commentaire"]) || $_POST["nom"] == "" || $_POST["prenom"] == ""){

    header("Location: ../index.php?error=nullError&action=reservation");
}
else {
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // début de la transaction
        $pdo->beginTransaction();
        $nom = htmlspecialchars($_POST["nom"]);
        $prenom = htmlspecialchars($_POST["prenom"]);
        $adresse = $_POST["adresse"] == "" ? null : htmlspecialchars($_POST["adresse"]);
        $numero = $_POST["numero"] == "" ? null : htmlspecialchars($_POST["numero"]);
        $localite = $_POST["localite"] == "" ? null : htmlspecialchars($_POST["localite"]);
        $cPostal = $_POST["cPostal"] == "" ? null : htmlspecialchars($_POST["cPostal"]);
        $telFixe = $_POST["noFixe"] == "" ? null : htmlspecialchars($_POST["noFixe"]);
        $telMob = $_POST["noGsm"] == "" ? null : htmlspecialchars($_POST["noGsm"]);
        $mail = $_POST["mail"] == "" ? null : htmlspecialchars($_POST["mail"]);
        $commentaire = htmlspecialchars($_POST["commentaire"]);


        //ajouter un spectateur et son adresse
        $spectator = new Spectator(array('nom' => $nom, 'prenom' => $prenom, 'rue' => $adresse,'numero' => $numero,
            'localite' => $localite,'codePostal' => $cPostal,'telFixe' => $telFixe,'telMobile' => $telMob,'adresseMail' => $mail,
            'commentaire' => $commentaire ));
        $spectatorManager = new SpectatorManager($pdo);
        $ok1 = $spectatorManager->ajouterSpectator($spectator);
        $ok2=$spectatorManager->ajouterAdresse($spectator);

        // transaction
        if ($ok1 && $ok2){
            $pdo->commit();
            $dbManager->disconnect();
            header("Location: ../index.php?action=reservation&success=true"); // si le spectateur à été ajoutée
        }else{
            $pdo->rollBack();
            $dbManager->disconnect();
            header("Location: ../index.php?action=reservation&error=spectateurExistError"); // si l'ajout du spectateur à échoué
        }
    }else{
        header("Location: ../index.php?action=reservation");
    }

}