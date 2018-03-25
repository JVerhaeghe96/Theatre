<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 06-03-18
 * Time: 14:33
 */
require_once "manager/AbstractManager.php";
require_once "manager/DBManager.php";
require_once "manager/SpectacleManager.php";
require_once "manager/RepresentationManager.php";
require_once "manager/ChaiseManager.php";

require_once "model/PO.php";
require_once "model/User.php";
require_once "model/Representation.php";
require_once "model/Chaise.php";

require_once "utils/AfficherTableauAdministration.php";
require_once "utils/DateUtils.php";
require_once "utils/AfficherPlanSalle.php";


//  Obligatoire pour pouvoir stocker et accéder aux variables de session
session_start();

echo '
<!DOCTYPE html>
<html>
<head>
    <title>Art et Récréation - index</title>
    <meta charset="utf-8"/>
    <link rel="SHORTCUT ICON" href="image/logo.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="logo">
        <img class="imglogo" src="image/logo1.png"/>
    </div>
</header>
<nav>
    <ul id="menu">
        <li><a href="index.php?action=salle">Salle</a></li>
        <li><a href="#">Spectacle</a>
            <ul>
                <li><a href="index.php?action=spectacle">Ajouter spectacle</a></li>
                <li><a href="index.php?action=acteur">Ajouter personnel</a></li>
                <li><a href="index.php?action=representation">Ajouter représentation</a></li>
                <li><a href="index.php?action=touteBoite">Exporter toute boîte</a></li>
            </ul>
        </li>
        <li><a href="#">Spectateurs</a>
            <ul>
                <li><a href="index.php?action=spectateur">Ajouter un spectateur</a></li>
                <li><a href="index.php?action=mail">Envoyer un mail	</a></li>
                <li><a href="index.php?action=etiquette">Exporter des étiquettes</a></li>
            </ul>
        </li>
        <li><a href="index.php?action=reservation">Reservation</a></li>
        <li><a href="index.php?action=liste">Listes</a></li>
        <li><a href="index.php?action=extraction">Extraction</a></li>
        <li><a href="index.php?action=admin">Administration</a></li>
    </ul>
</nav>';

if(!isset($_SESSION["connection"])){    //  page de connexion

    //  Cas d'erreurs
    if(isset($_GET["error"])){
        if($_GET["error"] == "nullError")   // Si le nom d'utilisateur ou le mot de passe n'ont pas été entrés.
            echo "<script>alert('Le login et le mot de passe sont obligatoire !')</script>";
        elseif($_GET["error"] == "badLoginMdp"){   //  Si le nom d'utilisateur ou le login sont incorrects.
            echo "<script>alert('Le login ou le mot de passe sont incorrects')</script>";
        }
    }

    //  le traitement de la connexion se fait dans le fichier TraitementLogin.php
    echo '
    <div id="content">
    <h1>CONNEXION</h1>
    <div class="content2">
        <form method="POST" action="traitements/TraitementLogin.php">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" placeholder="ex: admin " autofocus="autofocus" required/>
            </br> </br>
            <label for="mdp">Mot de passe:</label>
            <input type="password" name="mdp" id="mdp" placeholder="●●●●●●" required/>
            <input id="bouton" type="submit" value="Connexion"/>
        </form>
    </div>';
}else{      //  page d'accueil
    if(!isset($_GET["action"])){
        echo "<div id=\"content\">
    <table>
        <caption>ACCUEIL</caption>
        <tr>
            <th>Rubriques</th>
            <th>Descriptions</th>
        </tr>
        <tr>
            <td> <a href=\"index.php?action=salle\"><img src=\"image/salle.png\"/></a></td>
            <td>Modifier la disposition de la salle</td>
        </tr>
        <tr>
            <td>
                <img src=\"image/spectacle.png\"/></br>
                <a href=\"index.php?action=spectacle\"><img src=\"image/spectacleAjout.png\"/></a></br>
                <a href=\"index.php?action=acteur\"> <img src=\"image/spectaclePersonnel.png\"/></a></br>
                <a href=\"index.php?action=representation\"><img src=\"image/spectacleRepresentation.png\"/></a></br>
                <a href=\"index.php?action=touteBoite\"> <img src=\"image/spectacleTouteBoite.png\"/></a>
            </td>
            <td>
                Ajouter un spectacle </br>
                Ajouter un personnage </br>
                Ajouter un acteur </br>
                Ajouter une représentation</br>
                Exporter toute boîte
            </td>
        </tr>
        <tr>
            <td>
                <img src=\"image/spectateurs.png\"/></br>
                <a href=\"index.php?action=spectateur\"><img src=\"image/spectateursAjout.png\"/></a></br>
                <a href=\"index.php?action=mail\"><img src=\"image/spectateursMail.png\"/></a></br>
                <a href=\"index.php?action=etiquette\"><img src=\"image/spectateursEtiquette.png\"/></a>
            </td>
            <td>
                Ajouter un spectateur </br>
                Envoyer un mail </br>
                Exporter des étiquettes </br>
            </td>
        </tr>
        <tr>
            <td><a href=\"index.php?action=reservation\"><img src=\"image/reservation.png\"/></a></td>
            <td>Effectuer une réservation</td>
        </tr>
        <tr>
            <td><a href=\"index.php?action=liste\"><img src=\"image/listes.png\"/></a></td>
            <td>Modifier ou consulter la liste des spectateurs</td>
        </tr>
        <tr>
            <td><a href=\"index.php?action=extraction\"><img src=\"image/extraction.png\"/></a></td>
            <td>Exporter la liste des réservations pour une certaine date </td>
        </tr>
        <tr>
            <td><a href=\"index.php?action=admin\"><img src=\"image/administration.png\"/></a></td>
            <td>Ajouter rang utilisateur, modifier droits des utilisateurs</td>
        </tr>

    </table>";
    }
    else if($_GET["action"]=="admin"){
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }elseif($_GET["error"] == "badMdpError"){   //  Si les mots de passes ne correspondent pas
                echo "<script>alert('Mot de passe incorrects.');</script>";
            }elseif($_GET["error"] == "userExistError"){ //  Si l'utilisateur existe déjà.
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }
        }elseif(isset($_GET["success"])){
            if($_GET["success"] == "true"){
                echo "<script>alert('Nouvel utilisateur ajouté !');</script>";
            }
        }
        echo"
        <script type='text/javascript' src='js/admin.js'></script>
        <div id=\"content\">
            <table>
				<caption>ADMINISTRATION</caption>
				<tr>
					<th>Utilisateur</th>
					<th>Rubrique</th>
					<th>Consultation</th>
					<th>Maintenance</th>
				</tr>";

                afficherTableauAdministration();

                echo "
            </table>
			<input id=\"boutonAddRang\" type=\"submit\" value=\"Ajouter un nouveau utilisateur\"/>
			
			<div id='modal'></div>
			
			<div id='formNewUser'>
			    <button id='closeButton'>X</button>
                <form method='post' action='traitements/TraitementUser.php'>
                    <label for='newLogin'>Nouvel utilisateur</label>
                    <input type='text' name='login' id='newLogin'/>
                    <br/><br/>
                    <label for='mdpNewUser'>Mot de passe</label>
                    <input type='password' name='mdp' id='mdpNewUser'/>
                    <br/><br/>
                    <label for='mdpNewUserConfirm'>Confirmez le mot de passe</label>
                    <input type='password' name='mdpConfirm' id='mdpNewUserConfirm'/>
                    <br/><br/>
                    <input type='submit' id='bouton' value='Enregistrer un nouvel utilisateur'/>
                </form>
            </div>
        ";
    }else if ($_GET["action"]=="representation"){
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }elseif($_GET["error"] == "representationExistError"){ //  Si la representation existe déjà.
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }
        }elseif(isset($_GET["success"])){
            if($_GET["success"] == "true"){
                echo "<script>alert('Nouvelle représentation ajouté !');</script>";
            }
        }

        //  On obtient une connexion à la DB
        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        $spectacleManager=new SpectacleManager($pdo);
        $tabTitres= $spectacleManager->getAllTitles();


       echo "
       <div id=\"content\">
        
           <div class=\"formAdd content2\" id=\"representation\">
           <form  method='POST' action='traitements/TraitementRepresentation.php'>
                    <label for=\"titre\">Spectacle: </label>
                    <select name=\"titre\">
                    ";

       foreach ($tabTitres as $titres)
       {
           echo"
             <option value=\"$titres\">$titres</option>
           ";
       }
        echo"
             </select>
             <br/><br/>
             <label for=\"date\">Date de représentation:</label>
             <input type=\"date\" name=\"date\">
             <br/><br/>
             <button id=\"addRepresentation\" value=\"ajouter representation\">Ajouter représentation</button>
         </form>
           </div>
         
       ";
    }else if($_GET["action"] == "salle"){   //  salle



        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        $representationManager = new RepresentationManager($pdo);
        $dates = $representationManager->getAllDates();

        echo "
        <script type='text/javascript' src='js/salle.js'></script>
        <div id=\"content\">
			<h1 id=\"titreSalle\">SALLE</h1>
			<div class=\"content2 salle\">
				<form method=\"POST\">
					<label for=\"date\">Date de représentation:</label>
					<select name=\"date\">";

                        foreach($dates as $date){
                            echo "<option value='". $date['date'] ."'>". $date['date'] ."</option>";
                        }

                echo "
                    </select>
					<input id='rechercheSalle' class=\"bouton\" type=\"submit\" value=\"Rechercher salle\"/>
				</form>
			</div>";


        if(isset($_POST["date"])){
            $tabSalle = afficherPlanSalle($_POST["date"]);

            echo "
            <div class=\"content2 salle\">
                <input id='currentDate' type='hidden' value='". $_POST['date'] ."'/>
                <label for=\"dateCopie\">Choisir date:</label>
                <select id='dateCopie' name=\"date\">";

            foreach($dates as $date){
                echo "<option value='". $date['date'] ."'>". $date['date'] ."</option>";
            }

            echo "
                </select>
                <button id='copierPlanSalle' class='bouton'>Copier plan de salle vers une autre date</button>
			</div>";
            echo $tabSalle;
        }
			/*

			<table class=\"tabSalle\">";

        for($lignes = 'A'; $lignes <= 'J'; $lignes++){
            echo "<tr>";

            for($col = 1; $col <= 14; $col++){
                echo "<td class='tabSalle'>". $lignes .$col ."<br/><input type=\"button\" class=\"remove\" value=\"-\"/></td>";
            }

            echo "</tr>";
        }

        echo "
			</table>
			<label class=\"boutonSalle\" for=\"ligne\">Sélectionner une ligne:</label>
			<select name=\"ligne\">
				<option value=\"l1\">ligne1</option>
				<option value=\"l2\">ligne2</option>
				<option value=\"l3\">ligne3</option>
				<option value=\"l4\">ligne4</option>
			</select>
			<input id=\"bouton\" type=\"button\" value=\"Ajouter ligne\"/>
			</br>
			<label class=\"boutonSalle\" for=\"colonne\">Sélectionner une colonne:</label>
			<select name=\"colonne\">
				<option value=\"c1\">colonne1</option>
				<option value=\"c2\">colonne2</option>
				<option value=\"c3\">colonne3</option>
				<option value=\"c4\">colonne4</option>
			</select>
			<input id=\"bouton\" type=\"button\" value=\"Supprimer une colonne\"/>
        ";*/
    }
}

echo '
    <div class="footer">
        <footer>Copyright © 2018 HELHA. All rights reserved. Art et Récréation</footer>
    </div>

</div>

</body>
</html>';

