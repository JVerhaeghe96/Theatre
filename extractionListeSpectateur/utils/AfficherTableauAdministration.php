<?php

function afficherTableauAdministration(){
    //  On obtient une connexion à la DB
    $dbManager = new DBManager();
    $pdo = $dbManager->connect();

    $connexionManager = new AdminManager($pdo);

    $users = $connexionManager->getAllUsers();

    foreach($users as $user){
        echo "
        <tr>
            <td class='nom' rowspan='14'>". $user->getLogin() ."</td>
            <td>Salle</td>";
        if($user->getSalle() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='salle' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='salle' value='M' checked/></td>
            ";
        }else if($user->getSalle() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='salle' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='salle' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='salle' value='C'/></td>
            <td><input type='checkbox' class='droit' name='salle' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter spectacle</td>";
        if($user->getAjouterSpectacle() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='M' checked/></td>
            ";
        }else if($user->getAjouterSpectacle() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='C'/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectacle' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter personnel</td>";

        if($user->getAjouterPersonnel() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='M' checked/></td>
            ";
        }else if($user->getAjouterPersonnel() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='C'/></td>
            <td><input type='checkbox' class='droit' name='ajouterPersonnel' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Ajouter représentation</td>";

        if($user->getAjouterRepresentation() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='M' checked/></td>
            ";
        }else if($user->getAjouterRepresentation() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='C'/></td>
            <td><input type='checkbox' class='droit' name='ajouterRepresentation' value='M'/></td>
            ";
        }

        echo "
        </tr>
        <tr>
            <td>Exporter toutes boîtes</td>";

        if($user->getExporterToutesBoites() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='M' checked/></td>
            ";
        }else if($user->getExporterToutesBoites() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='C'/></td>
            <td><input type='checkbox' class='droit' name='exporterToutesBoites' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Modifier spectateur</td>";

        if($user->getModifierSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='M' checked/></td>
            ";
        }else if($user->getModifierSpectateur() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='C'/></td>
            <td><input type='checkbox' class='droit' name='modifierSpectateur' value='M'/></td>
            ";
        }

        echo"</tr>
        <tr>
            <td>Envoyer un mail</td>";

        if($user->getEnvoyerMail() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='envoyerMail' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='envoyerMail' value='M' checked/></td>
            ";
        }else if($user->getEnvoyerMail() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='envoyerMail' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='envoyerMail' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='envoyerMail' value='C'/></td>
            <td><input type='checkbox' class='droit' name='envoyerMail' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Exporter des étiquettes</td>";

        if($user->getExporterEtiquettes() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='M' checked/></td>
            ";
        }else if($user->getExporterEtiquettes() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='C'/></td>
            <td><input type='checkbox' class='droit' name='exporterEtiquettes' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Réservations</td>";

        if($user->getReservations() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='reservations' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='reservations' value='M' checked/></td>
            ";
        }else if($user->getReservations() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='reservations' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='reservations' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='reservations' value='C'/></td>
            <td><input type='checkbox' class='droit' name='reservations' value='M'/></td>
            ";
        }
        
        echo "</tr>
        <tr>
            <td>Ajouter un spectateur</td>";

        if($user->getAjouterSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='M' checked/></td>
            ";
        }else if($user->getAjouterSpectateur() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='C'/></td>
            <td><input type='checkbox' class='droit' name='ajouterSpectateur' value='M'/></td>
            ";
        }
        
        echo "</tr>
        <tr>
            <td>Rechercher une réservation</td>";

        if($user->getRechercherReservation() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='M' checked/></td>
            ";
        }else if($user->getRechercherReservation() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='C'/></td>
            <td><input type='checkbox' class='droit' name='rechercherReservation' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Rechercher un spectateur</td>";

        if($user->getRechercherSpectateur() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='M' checked/></td>
            ";
        }else if($user->getRechercherSpectateur() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='C'/></td>
            <td><input type='checkbox' class='droit' name='rechercherSpectateur' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Consulter liste</td>";

        if($user->getConsulterListe() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='consulterListe' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='consulterListe' value='M' checked/></td>
            ";
        }else if($user->getConsulterListe() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='consulterListe' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='consulterListe' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='consulterListe' value='C'/></td>
            <td><input type='checkbox' class='droit' name='consulterListe' value='M'/></td>
            ";
        }

        echo "</tr>
        <tr>
            <td>Extraction</td>";

        if($user->getExtraction() == 'M'){
            echo "
            <td><input type='checkbox' class='droit' name='extraction' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='extraction' value='M' checked/></td>
            ";
        }else if($user->getExtraction() == 'C'){
            echo "
            <td><input type='checkbox' class='droit' name='extraction' value='C' checked/></td>
            <td><input type='checkbox' class='droit' name='extraction' value='M' /></td>
            ";
        }else{
            echo "
            <td><input type='checkbox' class='droit' name='extraction' value='C'/></td>
            <td><input type='checkbox' class='droit' name='extraction' value='M'/></td>
            ";
        }

        echo "</tr>
        ";
    }
}
