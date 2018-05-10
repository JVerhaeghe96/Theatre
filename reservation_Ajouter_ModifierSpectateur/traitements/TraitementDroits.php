<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/AdminManager.php";
require_once "../model/PO.php";
require_once "../model/User.php";

header("Content-type: text/plain");
if(isset($_POST["user"])&& isset($_POST["droit"]) && isset($_POST["valeur"])){

    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $connexionManager = new AdminManager($pdo);

    $connexionManager->modifierDroit($_POST["user"], $_POST["droit"], $_POST["valeur"]);
}

