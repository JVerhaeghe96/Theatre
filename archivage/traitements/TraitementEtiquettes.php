<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 23-04-18
 * Time: 14:23
 */

require_once '../lib/JasperPHP/JasperPHP.php';

if(!isset($_POST["inclureMail"])){
    header("HTTP/1.1 403");
}else{
    $j = new \JasperPHP\JasperPHP();

    $file = $_POST["inclureMail"] == "true" ? "etiquettesAvecMail" : "etiquettesSansMail";

    $output = $j->process(
        '../jasper_reports/'. $file,
        "../jasper_reports/".$file,
        array('pdf'),
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