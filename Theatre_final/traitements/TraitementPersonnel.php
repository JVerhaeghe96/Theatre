<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\DBManager;
use app\manager\PersonnageManager;
use app\manager\PersonnelManager;
use app\model\Personnage;
use app\model\Personnel;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

header("Content-type: text/plain");

if(isset($_POST["spectacle"])&& isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["fonction"])){
    if($_POST["fonction"] == "acteur" && ((!isset($_POST["nomPers"]) || !isset($_POST["prenomPers"]))))
        header("HTTP/1.1 400");
    else {
        // connexion DB
        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        if($pdo != null){
            $personnelManager = new PersonnelManager($pdo);
            $personnageManager = new PersonnageManager($pdo);

            $nom = htmlspecialchars($_POST["nom"]);
            $prenom = htmlspecialchars($_POST["prenom"]);
            $fonction = htmlspecialchars($_POST["fonction"]);

            $personnel = new Personnel(array("nom" => $nom, "prenom" => $prenom, "fonction" => $fonction));

            // début de la transaction
            $pdo->beginTransaction();

            // ajouter personnel
            $ok = $personnelManager->ajouterPersonnel($personnel, $_POST["spectacle"]);

            // si acteur on crée le personnage
            if ($ok && $_POST["fonction"] == "acteur") {
                $nomPers = htmlspecialchars($_POST["nomPers"]);
                $prenomPers = htmlspecialchars($_POST["prenomPers"]);

                $personnage = new Personnage(
                    array("titre" => $_POST["spectacle"],
                        "nom" => $nomPers,
                        "prenom" => $prenomPers,
                        "id" => $personnel->getId()));

                // ajout du personnage
                $ok = $personnageManager->ajouterPersonnage($personnage);
            }

            // transaction
            if ($ok){
                $pdo->commit();
                echo "ajout effectué";
            }else {
                $pdo->rollBack();
                header("HTTP/1.1 406");
            }
            $dbManager->disconnect();
        }else{
            header("HTTP/1.1 500");
        }
    }
}else{
    header("HTTP/1.1 403");
}