<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\AdminManager;
use app\manager\DBManager;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["login"]) || !isset($_POST["mdp"]) || !isset($_POST["mdpConfirm"]) || $_POST["login"] == "" ||$_POST["mdp"] == "" || $_POST["mdpConfirm"]=="" ){
    header("Location: ../index.php?action=admin&error=nullError");
}else {
    if($_POST["mdp"]!=$_POST["mdpConfirm"]){
        header("Location: ../index.php?action=admin&error=mdpNotCorrespondingError");
    }

    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // ajouter un utilisateur
        $connexionManager = new AdminManager($pdo);

        $login = htmlspecialchars($_POST["login"]);
        $mdp = htmlspecialchars($_POST["mdp"]);

        $ok= $connexionManager->ajouterUser($login,$mdp);
        if($ok){
            $dbManager->disconnect();
            header("Location: ../index.php?action=admin&success=user");
        }
        else{
            $dbManager->disconnect();
            header("Location: ../index.php?action=admin&error=userExistError");
        }
    }else{
        header("Location: ../index.php?action=admin");
    }


}