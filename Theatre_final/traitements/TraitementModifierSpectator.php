<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\DBManager;
use app\manager\SpectatorManager;
use app\model\Spectator;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(isset($_GET["action"])){
    //  connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();


    if($_GET["action"]=="getId"){ // rechercher un spectateur
        header("Content-type: application/JSON");
        if($pdo != null){
            if(isset($_POST["nom"]) && isset($_POST["prenom"])){
                $spectatorManager = new SpectatorManager($pdo);
                $nom = htmlspecialchars($_POST["nom"]);
                $prenom = htmlspecialchars($_POST["prenom"]);
                $spectateur=$spectatorManager->getSpectatorByNomPrenom(new Spectator(array('nom' => $nom, 'prenom' => $prenom)));
                echo json_encode($spectateur);
            }else{
                header('HTTP1.1 403');
            }
        }else{
            header("HTTP/1.1 500");
        }

    }else if($_GET["action"]=="modifier") { //  modifier informations spectateur
        if (!isset($_POST["nom"]) || !isset($_POST["prenom"]) || !isset($_POST["adresse"]) || !isset($_POST["numero"])
            || !isset($_POST["localite"]) || !isset($_POST["cPostal"]) || !isset($_POST["noFixe"]) || !isset($_POST["noGsm"])
            || !isset($_POST["mail"]) || !isset($_POST["commentaire"]) || $_POST["nom"] == "" || $_POST["prenom"] == "" || $_POST["adresse"] == ""
            || $_POST["numero"] == "" || $_POST["localite"] == "" || $_POST["cPostal"] == ""){

            header("Location: ../index.php?error=nullError&action=spectateur");
        } else {
            if($pdo != null){
                $spectatorManager = new SpectatorManager($pdo);
                // début de la transaction
                $pdo->beginTransaction();

                $nom = htmlspecialchars($_POST["nom"]);
                $prenom = htmlspecialchars($_POST["prenom"]);
                $rue = htmlspecialchars($_POST["adresse"]);
                $numero = htmlspecialchars($_POST["numero"]);
                $localite = htmlspecialchars($_POST["localite"]);
                $cPostal = htmlspecialchars($_POST["cPostal"]);
                $telFixe = $_POST["noFixe"] == "" ? null : htmlspecialchars($_POST["noFixe"]);
                $telMobile = $_POST["noGsm"] == "" ? null : htmlspecialchars($_POST["noGsm"]);
                $adresseMail = $_POST["mail"] == "" ? null : htmlspecialchars($_POST["mail"]);
                $commentaire = htmlspecialchars($_POST["commentaire"]);

                $spectator = new Spectator(array('id' => $_POST["id"], 'nom' => $nom, 'prenom' => $prenom, 'rue' => $rue,
                    'numero' => $numero, 'localite' => $localite, 'codePostal' => $cPostal, 'telFixe' => $telFixe,
                    'telMobile' => $telMobile, 'adresseMail' => $adresseMail, 'commentaire' => $commentaire));

                $ancienSpectator = new Spectator($spectatorManager->getSpectatorByNomPrenom($spectator));

                $spectator->setId($ancienSpectator->getId());

                // modifier le spectateur et son adresse
                $ok1 = $spectatorManager->modifierSpectatorById($spectator);
                $ok2 = $spectatorManager->modifierAdresseById($spectator);

                var_dump($spectator);


                // transaction
                if ($ok1 && $ok2){
                    $pdo->commit();
                    header("Location: ../index.php?action=spectateur&success=true"); // si le spectateur à été modifié
                } else {
                    $pdo->rollBack();
                    header("Location: ../index.php?action=spectateur&error=spectateuModifierrExistError"); // si la modification du spectateur à échouée
                }
            }else{
                header("Location: ../index.php?action=spectateur");
            }

        }
    }
    $dbManager->disconnect();
}