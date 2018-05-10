<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 26-04-18
 * Time: 20:47
 */

require_once "../utils/MailUtils.php";
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/SpectatorManager.php";
require_once "../model/Spectator.php";

if(!isset($_POST["message"])){
    header("HTTP/1.1 406");
}else{
    $fichiers = array();

    if(isset($_POST["touteboite"]) && $_POST["touteboite"] == "true")
        array_push($fichiers, "../image/touteboite.png");

    if(isset($_POST["fichier"]) && sizeof($_POST["fichier"]) != 0){
        foreach ($_POST["fichier"] as $fichier) {
            $uploaddir = '../import/';
            $uploadfile = $uploaddir . basename($fichier);
            array_push($fichiers, $uploadfile);
        }
    }

    $dbManager = new DBManager();
    $pdo = $dbManager->connect();
    $spectateurManager = new SpectatorManager($pdo);

    $mailsSpectateurs = $spectateurManager->getAllSpectatorsMails();

    if(!MailUtils::sendMail($fichiers, "jujuver96@gmail.com",
        "julien verhaeghe", "jujuver96@gmail.com",
        $_POST["message"], "Test", $mailsSpectateurs)){
        header("HTTP/1.1 403");
    }
}