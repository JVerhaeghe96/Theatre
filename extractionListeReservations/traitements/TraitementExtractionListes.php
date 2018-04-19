<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 17-04-18
 * Time: 21:11
 */

require_once '../lib/JasperPHP/JasperPHP.php';
require_once '../utils/DateUtils.php';

header("Content-type: text/plain");

if(!isset($_GET["action"])){
    header("HTTP/1.1 403");
}else{
    if($_GET["action"] == "listeReservation" && isset($_POST["date"]) && !empty($_POST["date"])){
        $tab = explode("|", $_POST["date"]);
        $date = $tab[0];
        $heure = $tab[1];
        $tabHeure = explode(":", $heure);
        $outputFile = "ListeReservation_". $date ."_". $tabHeure[0] ."h". $tabHeure[1];

        $j = new \JasperPHP\JasperPHP();
        $output = $j->process(
            '../jasper_reports/ListeReservations.jasper',
            "../jasper_reports/".$outputFile,
            array('pdf'),
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
    }
}

