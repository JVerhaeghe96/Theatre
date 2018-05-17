<?php

namespace app\traitements;

require_once '../lib/JasperPHP/JasperPHP.php';

if(!isset($_POST["inclureMail"])){
    header("HTTP/1.1 403");
}else{
    $j = new \JasperPHP\JasperPHP();

    // nom du fichier qui sera créer
    $file = $_POST["inclureMail"] == "true" ? "etiquettesAvecMail" : "etiquettesSansMail";

    // connexion DB  et exécution du rapport
    $output = $j->process(
        '../jasper_reports/'. $file,
        "../jasper_reports/".$file,
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
        echo $file;
    else
        header("HTTP/1.1 406");

}