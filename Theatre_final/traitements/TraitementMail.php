<?php

namespace app\traitements;

use app\Autoloader;
use app\manager\DBManager;
use app\manager\SpectatorManager;
use app\utils\MailUtils;

require_once "../Autoloader.php";

// enregistrer un autoloader auprès du serveur
Autoloader::register();

if(!isset($_POST["message"])){
    header("HTTP/1.1 406");
}else{
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        // pièces jointes
        $fichiers = array();

        // garnissage du tableau
        if(isset($_POST["fichier"]) && sizeof($_POST["fichier"]) != 0){
            foreach ($_POST["fichier"] as $fichier) {
                $uploaddir = '../import/';
                $uploadfile = $uploaddir . basename($fichier);
                array_push($fichiers, $uploadfile);
            }
        }


        // récupérer toutes les adresses mail des spectateurs
        $spectateurManager = new SpectatorManager($pdo);
        $mailsSpectateurs = $spectateurManager->getAllSpectatorsMails();

        $message = htmlspecialchars($_POST["message"]);

        // mail
        if(!MailUtils::sendMail($fichiers, "jujuver96@gmail.com",
            "julien verhaeghe", "jujuver96@gmail.com",
            $message, "Test", $mailsSpectateurs)){
            header("HTTP/1.1 403");
        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }

}