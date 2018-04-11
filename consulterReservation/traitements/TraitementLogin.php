<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 10-03-18
 * Time: 10:36
 */

require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/AdminManager.php";
require_once "../model/PO.php";
require_once "../model/User.php";

//  Obligatoire pour pouvoir stocker et accéder aux variables de session
session_start();

//  Si le login ou le mot de passe n'ont pas été entrés.
//  Ça ne devrait normalement pas arriver, mais il vaut mieux prévoir
if(!isset($_POST["login"]) || !isset($_POST["mdp"]) || $_POST["login"] == "" ||$_POST["mdp"] == ""){
    header("Location: ../index.php?error=nullError");
    exit();
}else{
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $connexionManager = new AdminManager($pdo);

    //  On recherche les informations de l'utilisateur dans la DB
    $user = $connexionManager->connect($_POST["login"], $_POST["mdp"]);

    if($user instanceof User){ //  Si la connexion a réussie
        $_SESSION["connection"] = $user;
        header("Location: ../index.php");
    }else{  //  Si la connexion a échouée
        header("Location: ../index.php?error=badLoginMdp");
    }
}