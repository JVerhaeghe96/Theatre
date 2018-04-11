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
function afficherPlanSalle($date){
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $chaiseManager = new ChaiseManager($pdo);
    $chaises = $chaiseManager->getAllByDates($date);

    $tabSalle = "<table class='tabSalle'>";
    $i = 0;
    foreach($chaises as $chaise){
        if($i==0){
            $tabSalle .= "<tr>";
        }

        $bouton = $chaise->getEtat() == 'N' ?   //  Si l'état de la chaise est "non disponible"
            "<button class='add boutonChaise' value='". $chaise->getId() ."'>+</button>" :     //  alors ...
            "<button class='remove boutonChaise' value='". $chaise->getId() ."'>-</button>"; //  sinon ...

        $tabSalle .= "<td class='tabSalle'>". $chaise->getId() ."<br/>" .$bouton ."</td>";

        $i++;
        if($i==14){
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
    $chaises = $chaiseManager->getAllByDates($date);

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
        if($i==14){
            $i = 0;
            $tabSalle .= "</tr>";
        }
    }

    $tabSalle .= "</table>";

    return $tabSalle;
}