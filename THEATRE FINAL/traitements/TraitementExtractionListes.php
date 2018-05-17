<?php

namespace app\traitements;

require_once '../lib/JasperPHP/JasperPHP.php';
require_once '../utils/DateUtils.php';

header("Content-type: text/plain");

if(!isset($_GET["action"])){
    header("HTTP/1.1 403");
}else{
    $j = new \JasperPHP\JasperPHP();
    if($_GET["action"] == "listeReservation" && isset($_POST["date"]) && !empty($_POST["date"])){
        // récupérer date et l heure
        $tab = explode("|", $_POST["date"]);
        $date = $tab[0];
        $heure = $tab[1];
        $tabHeure = explode(":", $heure);
        $outputFile = "ListeReservation_". $date ."_". $tabHeure[0] ."h". $tabHeure[1];  // nom du fichier qui sera créer

        // connexion DB  et exécution du rapport
        $output = $j->process(
            '../jasper_reports/ListeReservations.jasper',
            "../jasper_reports/".$outputFile,
            array('pdf', 'xlsx'),
            array('date_spectacle' => $date, "heure_spectacle" => $heure),
            array(
                'driver' => 'mysql',
                'username' => 'root',
                'host' => 'localhost',
                'database' => 'theatre',
                'port' => '3306',
            )
        )->execute();

        if(count($output) == 0)
            echo $outputFile;
        else
            header("HTTP/1.1 406");
    }else if($_GET["action"]=="listeSpectateur"){
        $outputFile = "listeSpectateur"; // nom du fichier qui sera créer

        // connexion DB  et exécution du rapport
        $output = $j->process(
            '../jasper_reports/listeSpectateur.jasper',
            "../jasper_reports/".$outputFile,
            array('pdf', 'xlsx'),
            array(),
            array(
                'driver' => 'mysql',
                'username' => 'root',
                'host' => 'localhost',
                'database' => 'theatre',
                'port' => '3306',
            )
        )->execute();

        if(count($output) == 0)
            echo $outputFile;
        else
            header("HTTP/1.1 406");
    }
}

