<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 10-05-18
 * Time: 11:35
 */

namespace app\traitements;

use app\Autoloader;
use app\manager\AdminManager;
use app\manager\DBManager;

require_once "../Autoloader.php";

Autoloader::register();

if(!isset($_POST["login"]) || !isset($_POST["ancienMdp"]) || !isset($_POST["nouveauMdp"])
    || !isset($_POST["confirmerMdp"]) || empty($_POST["login"]) || empty($_POST["ancienMdp"])
    || empty($_POST["nouveauMdp"]) || empty($_POST["confirmerMdp"])){
    header("Location: ../index.php?action=admin&error=nullError");
}else{
    if($_POST["nouveauMdp"] != $_POST["confirmerMdp"]){ //  vérifier si le mot de passe et sa confirmation correspondent
        header("Location: ../index.php?action=admin&error=mdpNotCorrespondingError");
    }else{
        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        if($pdo != null){
            // vérifier si l'ancien mot de passe est correct
            $adminManager = new AdminManager($pdo);

            $login = htmlspecialchars($_POST["login"]);
            $ancienMdp = htmlspecialchars($_POST["ancienMdp"]);

            $user = $adminManager->connect($login, $ancienMdp);
            if($user == null){
                $dbManager->disconnect();
                header("Location: ../index.php?action=admin&error=badMdpError");
            }else{
                $newMdp = htmlspecialchars($_POST["nouveauMdp"]);

                $ok = $adminManager->modifierMdp($login, $newMdp);

                if($ok){
                    $dbManager->disconnect();
                    header("Location: ../index.php?action=admin&success=mdp");
                }else{
                    $dbManager->disconnect();
                    header("Location: ../index.php?action=admin&error=failError");
                }
            }
        }else{
            header("Location: ../index.php?action=admin");
        }
    }
}