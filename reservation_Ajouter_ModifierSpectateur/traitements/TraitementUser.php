<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/AdminManager.php";
require_once "../model/PO.php";
require_once "../model/User.php";

if(!isset($_POST["login"]) || !isset($_POST["mdp"]) || !isset($_POST["mdpConfirm"]) || $_POST["login"] == "" ||$_POST["mdp"] == "" || $_POST["mdpConfirm"]=="" ){
    header("Location: ../index.php?action=admin&error=nullError");
}else {
    if($_POST["mdp"]!=$_POST["mdpConfirm"]){
        header("Location: ../index.php?action=admin&error=badMdpError");
    }

    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $connexionManager = new AdminManager($pdo);
   $ok= $connexionManager->ajouterUser($_POST["login"],$_POST["mdp"]);
   if($ok)
       header("Location: ../index.php?action=admin&success=true");
   else
       header("Location: ../index.php?action=admin&error=userExistError");
}