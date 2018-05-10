<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 25-03-18
 * Time: 09:54
 */

/**
 * @param $date
 * @return string
 */
function afficherPlanSalle($date,$heure){
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $chaiseManager = new ChaiseManager($pdo);
    $chaises = $chaiseManager->getAllByDates($date,$heure);

    $tabSalle = "<table class='tabSalle'>";
    $i = 0;
    foreach($chaises as $chaise){
        $disable=$chaise->getEtat()=='R' || $chaise->getEtat()=='O' ?"disabled":"";
        if($i==0){
            $tabSalle .= "<tr>";
        }

        $bouton = $chaise->getEtat() == 'N' ?   //  Si l'état de la chaise est "non disponible"
            "<button class='add boutonChaise '  value='". $chaise->getId() ."' ".$disable.">+</button>" :     //  alors ...
            "<button class='remove boutonChaise' value='". $chaise->getId() ."'".$disable.">-</button>"; //  sinon ...

        $tabSalle .= "<td class='tabSalle'>". $chaise->getId() ."<br/>" .$bouton ."</td>";

        $i++;
        if($i==20){
            $i = 0;
            $tabSalle .= "</tr>";
        }
    }

    $tabSalle .= "</table>";

    return $tabSalle;
}

function afficherPlanSalleReservation($date){
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $chaiseManager = new ChaiseManager($pdo);
    $chaises = $chaiseManager->getAllByDates(explode('|',$_POST["date"])[0],explode('|',$_POST["date"])[1]);

    $tabSalle = "<table class='tabSalle'>";
    $i = 0;
    foreach($chaises as $chaise){
        if($i==0){
            $tabSalle .= "<tr>";
        }
        $classe=null;
        //etat de base des cases
        switch ($chaise->getEtat()){
            case 'D':{
                $classe='tabSalle dispo'; break;

            }
            case 'N':{
                $classe='tabSalle pasDispo'; break;

            }
            case 'O':{
                $classe='tabSalle occupe'; break;

            }
            case 'R':{
                $classe='tabSalle reserve'; break;

            }
        }

        $tabSalle .= "<td class='".$classe."'>". $chaise->getId() ."</td>";

        $i++;
        if($i==20){
            $i = 0;
            $tabSalle .= "</tr>";
        }
    }

    $tabSalle .= "</table>";

    return $tabSalle;
}