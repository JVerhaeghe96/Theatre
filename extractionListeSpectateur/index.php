<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 06-03-18
 * Time: 14:33
 */

require_once "manager/AbstractManager.php";
require_once "manager/DBManager.php";
require_once "manager/AdminManager.php";
require_once "manager/SpectacleManager.php";
require_once "manager/RepresentationManager.php";
require_once "manager/ChaiseManager.php";
require_once "manager/SpectatorManager.php";

require_once "model/PO.php";
require_once "model/User.php";
require_once "model/Chaise.php";
require_once "model/Spectator.php";

require_once "utils/AfficherTableauAdministration.php";
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
    <link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.12.1.custom/jquery-ui.theme.css"/>
    <link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.12.1.custom/jquery-ui.structure.css"/>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script type="application/javascript"src="lib/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
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
                <li><a href="index.php?action=spectateur">Modifier un spectateur</a></li>
                <li><a href="index.php?action=mail">Envoyer un mail	</a></li>
                <li><a href="index.php?action=etiquette">Exporter des étiquettes</a></li>
            </ul>
        </li>
        <li><a href="index.php?action=reservation">Reservation</a></li>
         <li><a href="#">Listes</a>
            <ul>
                <li><a href="index.php?action=rechercherReservation">Consulter réservation </a></li>
                <li><a href="index.php?action=rechercherSpectateur">Consulter spectateur	</a></li>
                <li><a href="index.php?action=consulterListe">Consulter liste</a></li>
            </ul>
        </li>
        
        <li><a href="index.php?action=extraction">Extraction</a></li>
        <li><a href="index.php?action=admin">Administration</a></li>
    </ul>
</nav>
    <div id="content">';

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
        echo "
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
    else
        if($_GET["action"]=="admin"){
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
            <table>
				<caption>ADMINISTRATION</caption>
				<tr><td colspan='4'><input id=\"boutonAddRang\" type=\"submit\" value=\"Ajouter un nouveau utilisateur\"/></td></tr>
				<tr>
					<th>Utilisateur</th>
					<th>Rubrique</th>
					<th>Consultation</th>
					<th>Maintenance</th>
				</tr>";

                afficherTableauAdministration();

                echo "
            </table>
			
			<div id='modal'></div>
			
			<div id='formNewUser'>
			    <button class='button' id='closeButton'>X</button>
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
    }else
        if($_GET["action"]=="spectacle"){
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }elseif($_GET["error"] == "titleExistError"){ //  Si le spectacle existe déjà.
                echo "<script>alert('Un spectacle portant ce nom existe déjà.');</script>";
            }
        }elseif(isset($_GET["success"])){
            if($_GET["success"] == "true"){
                echo "<script>alert('Nouveau spectacle ajouté !');</script>";
            }
        }
        echo "
			<h1 id=\"titreSpectacle\">SPECTACLE</h1>
			<div class=\"contentSpectacle\" id=\"spectacle\">
				<form method=\"POST\" action=\"traitements/TraitementSpectacle.php\">
					<label for=\"titre\">Titre du spectacle:</label>
					<input type=\"text\" name=\"titre\"/>
					<br/><br/>
					<label for=\"resume\">Résumé du spectacle:</label>
					<textarea name=\"resume\"></textarea>
					<br/>
					<input id=\"bouton\" type=\"submit\" value=\"Ajouter spectacle\"/>
				</form>
			</div>
		";
    }else
        if($_GET["action"]=="acteur"){

        $dbManager = new DBManager();
        $pdo = $dbManager->connect();

        $spectacleManager = new SpectacleManager($pdo);
        $titres = $spectacleManager->getAllTitles();

        echo "
        <script type='text/javascript' src='js/personnel.js'></script>
			<h1 id=\"titreSpectacle\">SPECTACLE</h1>
			<div class=\"formAdd content2\" id=\"acteur\">
				<label for=\"titreSpectacle\">Spectacle: </label>
				<select name=\"spectacle\" id='titre'>";

        foreach($titres as $titre){
            echo "<option value=\"$titre\">$titre</option>";
        }

        echo "
                </select>
				<br/><br/>
				<label for=\"nom\">Nom de la personne:</label>
				<input type=\"text\" name=\"nom\" id='nomActeur'/>
				<br/><br/>
				<label for=\"prenom\">Prénom de la personne:</label>
				<input type=\"text\" name=\"prenom\" id='prenomActeur'/>
				<br/><br/>
				<label for=\"fonction\">Fonction de la personne:</label>
				<select name=\"fonction\" id=\"fonction\">
					<option value=\"regie\">Régie</option>
					<option value=\"acteur\">Acteur</option>
					<option value=\"aide-emoire\">Aide-mémoire</option>
					<option value=\"mise en scene\">Mise en scène</option>
				</select>
				<br/><br/>
				<div id=\"personnage\">
					<label for=\"nomPers\">Nom du personnage: </label>
					<input type=\"text\" name=\"nomPers\" id='nomPers'/>
					<br/><br/>
					<label for=\"prenomPers\">Prénom du personnage: </label>
					<input type=\"text\" name=\"prenomPers\" id='prenomPers'/>
					<br/><br/>
				</div>
				<button class='button' id=\"addActeurDB\" value=\"ajouter acteur\">Ajouter personnel</button>
			</div>
        ";
    }else
        if($_GET["action"]=="representation"){  //representation
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                echo "<script>alert('Veuillez compléter tout les champs.');</script>";
            }elseif($_GET["error"] == "representationExistError"){ //  Si la representation existe déjà.
                echo "<script>alert('Une représentation existe déjà à cette date.');</script>";
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
             <input type=\"date\" name=\"date\"/>
             <br/><br/>
             <label for=\"heure\">Heure de représentation:</label>
		     <input type=\"time\" name=\"heure\"/>
             <br/><br/>
             <button id=\"addRepresentation\" value=\"ajouter representation\">Ajouter représentation</button>
         </form>
           </div>
         
       ";
    }else
        if($_GET["action"] == "salle"){   //  salle

            $dbManager = new DBManager();
            $pdo = $dbManager->connect();

            $representationManager = new RepresentationManager($pdo);
            $dates = $representationManager->getAllDates();

            echo "
    <script type='text/javascript' src='js/salle.js'></script>
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
                </br><br/>
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
    }else
        if($_GET["action"] == "reservation") {

            if (isset($_GET["error"])) {
                if ($_GET["error"] == "nullError") { //  Si l'un des champs est manquant
                    echo "<script>alert('Veuillez compléter tout les champs.');</script>";
                } elseif ($_GET["error"] == "spectateurExistError") { //  Si le spectateur existe déjà.
                    echo "<script>alert('Veuillez compléter tout les champs.');</script>";
                }
            } elseif (isset($_GET["success"])) {
                if ($_GET["success"] == "true") {
                    echo "<script>alert('Nouveau spectateur ajouté !');</script>";
                }
            }
            //  On obtient une connexion à la DB
            $dbManager = new DBManager();
            $pdo = $dbManager->connect();

            $spectacleManager = new SpectacleManager($pdo);
            $tabTitres = $spectacleManager->getAllTitles();
            echo "
    <script type='text/javascript' src='js/reservation.js'></script>
    <div class=\"content2 salle reservation\">
        <form method=\"POST\" action='index.php?action=reservation'>
                <label>Titre du spectacle:</label>
                <select id='titre' name=\"titre\"> 
                <option></option>
                ";
            foreach ($tabTitres as $titres) {
                echo "
         <option value=\"$titres\">$titres</option>
       ";
            }


            echo "
                </select>
                <br/><br/>
                <label for=\"date\">Date de représentation:</label>
                <select id=\"date\" name=\"date\">
                </select>
                </br></br>
                <input id=\"boutonrech\" type=\"submit\" value=\"Rechercher représentation\"/>
        </form>
    </div>
    <br/><br/>
    ";
            if(isset($_POST["date"])) {
                $temp=explode('|',$_POST['date']);
                $date=$temp[0];
                $heure=$temp[1];
         //  On obtient une connexion à la DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                $spectatorManager = new SpectatorManager($pdo);
                $tabNom = $spectatorManager->getAllSpectators();
                echo "
    <div class=\"content3 salle reservationbis\">
                <input type='hidden' id='titreSelect' value='". $_POST['titre'] ."'/>
                <input type='hidden' id='dateSelect' value='". $date ."'/>
                <input type='hidden' id='heureSelect' value='". $heure ."'/>
                <label for=\"nom\">Nom de la personne :</label>
                <select  id='specId' name=\"nom\">
        ";

                foreach ($tabNom as $noms) {
                    echo "
                <option value='".$noms['id']."'>".$noms['nom']." ".$noms['prenom']."</option>
       ";
                }

                echo"
                    
                </select>
                </br></br>
                <input class=\"button\" id=\"boutonAjoutSpectateur\" type=\"button\" value=\"Ajouter un spectateur\"/>
                </br></br>
                <label for=\"nbPlaces\">Nombre de places: </label>
                <input type=\"number\" id='nbPlaces' name=\"nbPlaces\"/>
                </br></br>
                <label for=\"commentaire\">Commentaire :</label>
                <textarea name=\"commentaire\" id=\"commentaire\"></textarea>
                </br></br>
                <input class=\"button\" id=\"boutonReserver\" type=\"submit\" value=\"Réserver\"/>
            
        </div>
        
    <div id='modal'></div>	
    <div class=\"contentForm\" id=\"formNewSpectator\">
            <button class='button' id='closeButton'>X</button>
            <form method=\"POST\" action=\"traitements/TraitementAjoutSpectator.php\">
                <label for=\"nom\">Nom:</label>
                <input type=\"text\" name=\"nom\"/>
                <br/><br/>
                <label for=\"prenom\">Prénom:</label>
                <input type=\"text\" name=\"prenom\"/>
                <br/><br/>
                <label for=\"rue\">Rue:</label>
                <input type=\"text\" name=\"adresse\"/>
                <br/><br/>
                <label for=\"numero\">Numéro:</label>
                <input type=\"number\" name=\"numero\"/>
                <br/><br/>
                <label for=\"localite\">Ville:</label>
                <input type=\"text\" name=\"localite\"/>
                <br/><br/>
                <label for=\"cPostal\">Code postal:</label>
                <input type=\"number\" name=\"cPostal\"/>
                <br/><br/>
                <label for=\"noFixe\">Téléphone fixe:</label>
                <input type=\"number\" name=\"noFixe\"/>
                <br/><br/>
                <label for=\"noGsm\">Téléphone mobile:</label>
                <input type=\"number\" name=\"noGsm\"/>
                <br/><br/>
                <label for=\"mail\">Adresse mail:</label>
                <input type=\"mail\" name=\"mail\"/>
                <br/><br/>
                <label for=\"commentaire\">Commentaire:</label>
                <textarea name=\"commentaire\"></textarea>
                <br/>
                <input class=\"button\" id=\"bouton\" type=\"submit\" value=\"Enregistrer spectateur\"/>
            </form>
        </div>
        <img name='dispo' class=\"legende\" src=\"image/vert.png\" id=\"etatSelect\"> Disponible
        <img name='pasDispo' class=\"legende\" src=\"image/gris.png\"> Non disponible
        <img name='occupe' class=\"legende\" src=\"image/orange.png\"> Occupé 
        <img name='reserve' class=\"legende\" src=\"image/rouge.png\"> Réservé
    ";

                echo afficherPlanSalleReservation($date);
            }
        }else
        if($_GET["action"]=="spectateur"){

                if(isset($_GET["error"])) {
                    if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                        echo "<script>alert('Veuillez compléter tout les champs.');</script>";
                    }elseif($_GET["error"] == "spectateurModifierExistError"){ //  Si le spectateur n'a pas été modifier.
                        echo "<script>alert('Veuillez compléter tout les champs.');</script>";
                    }
                }elseif(isset($_GET["success"])){
                    if($_GET["success"] == "true"){
                        echo "<script>alert('Spectateur modifié !');</script>";
                    }
                }

                echo"
       <script type='application/javascript' src='js/spectateur.js'></script>
       <script type='application/javascript' src='js/liste.js'></script>
            <div class=\"contentForm\" id=\"ajout\">
                <form method=\"POST\" action=\"traitements/TraitementModifierSpectator.php?action=modifier\">
                    <label for=\"nom\">Nom:</label>
			        <input type=\"text\" name=\"nom\" id=\"nom\" autofocus=\"autofocus\" size=\"15\"/>
			        </br> </br>
			        <label for=\"nom\">Prénom:</label>
			        <input type=\"text\" name=\"prenom\" id=\"prenom\" autofocus=\"autofocus\" size=\"15\"/>
                    <br/><br/>
                    <label for=\"rue\">Rue:</label>
                    <input id='rue'type=\"text\" name=\"adresse\"/>
                    <br/><br/>
                    <label for=\"numero\">Numéro:</label>
                    <input id='numero'type=\"number\" name=\"numero\"/>
                    <br/><br/>
                    <label for=\"localite\">Ville:</label>
                    <input id='localite'type=\"text\" name=\"localite\"/>
                    <br/><br/>
                    <label for=\"cPostal\">Code postal:</label>
                    <input id='cPostal'type=\"number\" name=\"cPostal\"/>
                    <br/><br/>
                    <label for=\"noFixe\">Téléphone fixe:</label>
                    <input id='noFixe'type=\"number\" name=\"noFixe\"/>
                    <br/><br/>
                    <label for=\"noGsm\">Téléphone mobile:</label>
                    <input id='noGsm' type=\"number\" name=\"noGsm\"/>
                    <br/><br/>
                    <label for=\"mail\">Adresse mail:</label>
                    <input id='mail' type=\"mail\" name=\"mail\"/>
                    <br/><br/>
                    <label for=\"commentaire\">Commentaire:</label>
                    <textarea id='commentaire' name=\"commentaire\"></textarea>
                    <br/>
                    <input id=\"bouton\" type=\"submit\" value=\"modifier spectateur\"/>
                </form>
            </div>
            ";
        }else
        if($_GET["action"]=="rechercherReservation"){

                //  On obtient une connexion à la DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                $spectatorManager = new SpectatorManager($pdo);
                $tabNom = $spectatorManager->getAllSpectators();

        echo"
        	 <script type='text/javascript' src='js/liste.js'></script>
			<h1 id=\"titreListe\">LISTE</h1>
			<div class=\"content3\" id=\"formSpectateur\">
			<label>Titre du spectacle:</label>
                <select id='titre' name=\"titre\"> 
                <option></option>";

        foreach ($tabTitres as $titres) {
                echo "
         <option value=\"$titres\">$titres</option>";
        }
        echo"
            </select>
            </br></br>
			    <label for=\"date\">Date de représentation:</label>
                <select id=\"date\" name=\"date\">
                </select>
                </br></br>
					<label for=\"nom\">Nom:</label>
			        <input type=\"text\" name=\"nom\" id=\"nom\" autofocus=\"autofocus\" size=\"15\"/>
			        </br> </br>
			        <label for=\"nom\">Prénom:</label>
			        <input type=\"text\" name=\"prenom\" id=\"prenom\" autofocus=\"autofocus\" size=\"15\"/>
                 
					</br></br>
					<label for=\"numero\">Numéro de réservation:</label>
					<input type=\"number\" name=\"numReservation\" id=\"numReservation\" autofocus=\"autofocus\" size=\"15\"/>
					<input id=\"rechercherReservation\" type=\"submit\" value=\"Rechercher réservation\"/>
			
			</div>
			<div id='afficheReservation'></div>
        ";

        }else
        if($_GET["action"] == "rechercherSpectateur"){
            echo"
                <script src='js/liste.js'></script>
                    <div class=\"content2\" id=\"formSpectateur\">
                        <label for=\"nom\">Nom de la personne:</label>
                        <input type=\"text\" name=\"nom\" id=\"nom\" autofocus=\"autofocus\" size=\"15\"/>
                        </br></br>
                        <label for=\"nom\">Prénom de la personne:</label>
                        <input type=\"text\" name=\"prenom\" id=\"prenom\" autofocus=\"autofocus\" size=\"15\"/>
                        <input id=\"rechercherSpectateur\" type=\"submit\" value=\"Rechercher spectateur\"/>
                  
                    </div>
                <div id='afficherSpectateur'></div>
            
            ";


        }else
        if($_GET["action"]=="consulterListe"){

            $dbManager = new DBManager();
            $pdo = $dbManager->connect();

            $spectacleManager = new SpectacleManager($pdo);
            $tabTitres = $spectacleManager->getAllTitles();
            echo"
                    <script src='js/liste.js'></script>
                    <div class=\"content3\" id=\"formListe\">
                        <span class=\"radioButton\">Liste des réservations<input type=\"radio\" class=\"radioListe\" name=\"liste\" value=\"listeReservation\"></span> 
                        <span class=\"radioButton\">Liste des spectateurs<input type=\"radio\" class=\"radioListe\" name=\"liste\" value=\"listeSpectateurs\"></span>
                        <br/> <br/>
                        <div id=\"listeDate\" style=\"display : none;\">
                            <label>Titre du spectacle:</label>
                            <select id='titre' name=\"titre\"> 
                                <option></option>";

                                foreach ($tabTitres as $titres) {
                                    echo "
                                            <option value=\"$titres\">$titres</option>";
                                }
            echo"
                            </select>
                            <br/><br/>
                            
                            <label for=\"date\">Date de représentation:</label>
                            <select id='date' name=\"date\">
                            </select>
                        </div>
                        <br/>
                        <input id=\"consulterListe\" type=\"submit\" value=\"Consutler liste\"/>
                    </div>
                    <br/>
                    <div id='afficherListe'></div>
                
            ";

        }else
        if($_GET["action"] == "extraction"){
            $dbManager = new DBManager();
            $pdo = $dbManager->connect();

            $spectacleManager = new SpectacleManager($pdo);
            $tabTitres = $spectacleManager->getAllTitles();
            echo "
            <h1 id=\"titreExtraction\">EXTRACTION</h1>
                    <script src='js/extraction.js'></script>
                    <div class=\"content3\" id=\"formListe\">
                        <span class=\"radioButton\">Liste des réservations<input type=\"radio\" class=\"radioListe\" name=\"liste\" value=\"listeReservation\"></span> 
                        <span class=\"radioButton\">Liste des spectateurs<input type=\"radio\" class=\"radioListe\" name=\"liste\" value=\"listeSpectateurs\"></span>
                        <br/> <br/>
                        <div id=\"listeDate\" style=\"display : none;\">
                            <label>Titre du spectacle:</label>
                            <select id='titre' name=\"titre\"> 
                                <option></option>";

                                foreach ($tabTitres as $titres) {
                                    echo "
                                            <option value=\"$titres\">$titres</option>";
                                }
            echo"
                            </select>
                            <br/><br/>
                            
                            <label for=\"date\">Date de représentation:</label>
                            <select id='date' name=\"date\">
                            </select>
                        </div>
                        <br/>
                        <input class='button' id=\"extractList\" type=\"submit\" value=\"Extraire\"/>
                        <br/><br/>
                        <a target='_blank' class='button' href='' id='lien' style='display:none'>Télécharger le document</a>
                    </div>
            ";
        }
}

echo '
    <div class="footer">
        <footer>Copyright © 2018 HELHA. All rights reserved. Art et Récréation</footer>
    </div>

</div>

</body>
</html>';

