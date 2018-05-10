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


$fichiers = array();
if(isset($_POST["touteboite"]))
    array_push($fichiers, "../image/touteboite.png");
if($_FILES["fichier"]["name"] != ""){
    $uploaddir = '../import/';
    $uploadfile = $uploaddir . basename($_FILES['fichier']['name']);
    move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadfile);
    array_push($fichiers, $uploadfile);
}

$dbManager = new DBManager();
$pdo = $dbManager->connect();
$spectateurManager = new SpectatorManager($pdo);

$mailsSpectateurs = $spectateurManager->getAllSpectatorsMails();

MailUtils::sendMail($fichiers, "jujuver96@gmail.com",
    "julien verhaeghe", "jujuver96@gmail.com",
    "Test d'envoi de fichier", "Test", $mailsSpectateurs);

header("Location: ../index.php?action=mail");