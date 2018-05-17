<?php

namespace app\traitements;


use app\Autoloader;
use app\manager\AdminManager;
use app\manager\DBManager;
use app\model\User;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

// début de la session
session_start();

//  Si le login ou le mot de passe n'ont pas été entrés.
if(!isset($_POST["login"]) || !isset($_POST["mdp"]) || $_POST["login"] == "" ||$_POST["mdp"] == ""){
    header("Location: ../index.php?error=nullError");
}else{
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        //  On recherche les informations de l'utilisateur
        $connexionManager = new AdminManager($pdo);

        $login = htmlspecialchars($_POST["login"]);
        $mdp = htmlspecialchars($_POST["mdp"]);

        $user = $connexionManager->connect($login, $mdp);

        // Si la connexion a réussie
        if($user instanceof User){
            $_SESSION["connection"] = serialize($user);
            $dbManager->disconnect();
            header("Location: ../index.php");
        }else{  //  Si la connexion a échouée
            $dbManager->disconnect();
            header("Location: ../index.php?error=badLoginMdp");
        }
    }else{
        header("HTTP/1.1 500");
    }


}