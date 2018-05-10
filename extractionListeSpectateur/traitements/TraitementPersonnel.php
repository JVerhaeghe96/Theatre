<?php

require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/PersonnelManager.php";
require_once "../manager/PersonnageManager.php";
require_once "../model/PO.php";
require_once "../model/Personnel.php";
require_once "../model/Personnage.php";

header("Content-type: text/plain");

if(isset($_POST["spectacle"])&& isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["fonction"])){
    if($_POST["fonction"] == "acteur" && (!isset($_POST["nomPers"]) || !isset($_POST["prenomPers"])))
        header("HTTP/1.1 400");
    else {
        //  On obtient une connexion à la DB
        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        $personnelManager = new PersonnelManager($pdo);
        $personnageManager = new PersonnageManager($pdo);

        $personnel = new Personnel(array("nom" => $_POST["nom"], "prenom" => $_POST["prenom"], "fonction" => $_POST["fonction"]));

        $pdo->beginTransaction();

        $ok = $personnelManager->ajouterPersonnel($personnel, $_POST["spectacle"]);

        var_dump($ok);

        if ($ok && $_POST["fonction"] == "acteur") {
            $personnage = new Personnage(
                array("titre" => $_POST["spectacle"],
                    "nom" => $_POST["nomPers"],
                    "prenom" => $_POST["prenomPers"],
                    "persNom" => $_POST["nom"],
                    "persPrenom" => $_POST["prenom"]));

            $ok = $personnageManager->ajouterPersonnage($personnage);
            var_dump($ok);
        }

        if ($ok) {
            $pdo->commit();
            echo "ajout effectué";
        } else {
            $pdo->rollBack();
            header("HTTP/1.1 405");
        }
    }

}else{
    header("HTTP/1.1 405");
}