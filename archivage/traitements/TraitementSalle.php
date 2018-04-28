<?php
require_once "../manager/AbstractManager.php";
require_once "../manager/DBManager.php";
require_once "../manager/ChaiseManager.php";
require_once "../model/Chaise.php";
require_once "../utils/DateUtils.php";

header("Content-type: text/plain");
if(isset($_GET["action"])){
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    if($pdo != null){
        $chaiseManager = new ChaiseManager($pdo);
        if($_GET["action"] == "copier"){
            if(isset($_POST["dateCopie"]) && isset($_POST["currentDate"])){

                $pdo->beginTransaction();

                $ok1 = $chaiseManager->deleteAllByDate($_POST["dateCopie"]);
                $ok2 = $chaiseManager->copierPlanSalle($_POST["currentDate"], $_POST["dateCopie"]);

                if($ok1 && $ok2){
                    $pdo->commit();
                }else{
                    $pdo->rollBack();
                    header("HTTP/1.1 400");
                }
            }
        }else if($_GET["action"] == "changerEtat"){
            if(isset($_POST["id"]) && isset($_POST["date"])){
                $chaise = $chaiseManager->getByIdAndDate($_POST["id"], explode("|",$_POST["date"])[0],explode("|",$_POST["date"])[1]);
                if($chaise->getEtat() == 'O' || $chaise->getEtat() == 'R'){
                    header("HTTP/1.1 400");
                }else{
                    if($chaise->getEtat() == 'D')
                        $chaise->setEtat("N");
                    else
                        $chaise->setEtat("D");

                    $ok = $chaiseManager->modifierEtat($chaise);

                    if(!$ok)
                        header("HTTP/1.1 400");
                }
            }
        }
    }else{
        header("HTTP/1.1 500");
    }


}