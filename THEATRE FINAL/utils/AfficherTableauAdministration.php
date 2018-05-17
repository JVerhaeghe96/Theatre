<?php

namespace app\utils;

use app\manager\AdminManager;
use app\manager\DBManager;

function afficherTableauAdministration(){
    // connexion DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    // récupérer tous les utilisateurs
    $connexionManager = new AdminManager($pdo);
    $users = $connexionManager->getAllUsers();

    // tableau utilisateur
    foreach($users as $user){
        $enabled = ""; // activer ou non
        if($user->getLogin() == "admin")
            $enabled = "disabled";
        echo "
        <tr>
            <td rowspan='14'><span class='nom'>". $user->getLogin() ."</span><br/><button class='button boutonModifierMdp'>Changer mot de passe</button></td>
            <td>Salle</td>";
        if($user->getSalle() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='salle' value='M' ". $enabled ." checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='salle' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter spectacle</td>";
        if($user->getAjouterSpectacle() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter personnel</td>";

        if($user->getAjouterPersonnel() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter représentation</td>";

        if($user->getAjouterRepresentation() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Exporter toutes boîtes</td>";

        if($user->getExporterToutesBoites() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Modifier spectateur</td>";

        if($user->getModifierSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='M'/></td>
            ";
        }

        echo"</tr>
        <tr>
            <td>Envoyer un mail</td>";

        if($user->getEnvoyerMail() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='envoyerMail' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='envoyerMail' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Exporter des étiquettes</td>";

        if($user->getExporterEtiquettes() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Réservations</td>";

        if($user->getReservations() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='reservations' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='reservations' value='M'/></td>
            ";
        }
        
        echo "</tr>
        <tr>
            <td>Ajouter un spectateur</td>";

        if($user->getAjouterSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='M'/></td>
            ";
        }
        
        echo "</tr>
        <tr>
            <td>Rechercher une réservation</td>";

        if($user->getRechercherReservation() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Rechercher un spectateur</td>";

        if($user->getRechercherSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Consulter liste</td>";

        if($user->getConsulterListe() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='consulterListe' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='consulterListe' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Extraction</td>";

        if($user->getExtraction() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='extraction' value='M' ". $enabled ."  checked/></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='extraction' value='M'/></td>
            ";
        }

        echo "</tr>
        ";
    }
}
