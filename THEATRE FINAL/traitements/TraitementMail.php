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

        //  récupérer l'email de l'expéditeur
        $handle = fopen("../utils/from_mail.txt", 'r');
        $from_mail = fread($handle, filesize("../utils/from_mail.txt"));
        fclose($handle);

        //  récupérer le nom de l'expéditeur
        $handle = fopen("../utils/from_name.txt", 'r');
        $from_name = fread($handle, filesize("../utils/from_name.txt"));
        fclose($handle);

        // récupérer toutes les adresses mail des spectateurs
        $spectateurManager = new SpectatorManager($pdo);
        $mailsSpectateurs = $spectateurManager->getAllSpectatorsMails();

        $message = htmlspecialchars($_POST["message"]);

        // mail
        if(!MailUtils::sendMail($fichiers, $from_mail,
            $from_name, null,
            $message, "Test", $mailsSpectateurs)){
            header("HTTP/1.1 403");
        }
        $dbManager->disconnect();
    }else{
        header("HTTP/1.1 500");
    }

}