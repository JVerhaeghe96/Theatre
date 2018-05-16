<?php

namespace app;

use \app\manager\DBManager;
use app\manager\RepresentationManager;
use app\manager\SpectacleManager;
use app\manager\SpectatorManager;
use function app\utils\afficherPlanSalle;
use function app\utils\afficherPlanSalleReservation;
use function app\utils\afficherTableauAdministration;

require_once "Autoloader.php";
require_once "utils/AfficherTableauAdministration.php";
require_once "utils/AfficherPlanSalle.php";

// début de session
session_start();

// enregistrer un autoloader auprès du serveur
Autoloader::register();


if(isset($_GET["deconnect"]) && $_GET["deconnect"] == true){ // fin de session
    unset($_SESSION["connection"]);
}

echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Art et Récréation - index</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" charset="UTF-8"/>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.12.1.custom/jquery-ui.theme.css"/>
        <link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.12.1.custom/jquery-ui.structure.css"/>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
        <script type="application/javascript" src="lib/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
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
                </ul>
            </li>
            <li><a href="#">Spectateurs</a>
                <ul>
                    <li><a href="index.php?action=spectateur">Modifier spectateur</a></li>
                    <li><a href="index.php?action=mail">Envoyer mail</a></li>
                    <li><a href="index.php?action=etiquette">Exporter étiquettes</a></li>
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
            <li id="deco"><a href="index.php?deconnect=true"><img src="image/deconnexion.png"/></a></li>
        </ul>
    </nav>
        <div id="content">';

if(!isset($_SESSION["connection"])){    //  page de connexion
   echo" <h1>CONNEXION</h1>";

    if(isset($_GET["error"])){    //  Cas d'erreurs
        if($_GET["error"] == "nullError")   // Si le nom d'utilisateur ou le mot de passe n'ont pas été entrés.
            echo "<div class=\"alert alert-danger\" role=\"alert\">Le login et le mot de passe sont obligatoire !</div>";

        elseif($_GET["error"] == "badLoginMdp"){   //  Si le nom d'utilisateur ou le login sont incorrects.
            echo "<div class=\"alert alert-danger\" role=\"alert\">Le login ou le mot de passe sont incorrects</div>";
        }
    }

    //formulaire de connexion
    echo ' 
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
}else{
    if(!isset($_GET["action"])){ //  page d'accueil
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
                    </td>
                    <td>
                        Ajouter un spectacle </br>
                        Ajouter un personnage </br>
                        Ajouter un acteur </br>
                        Ajouter une représentation
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
                        Modifier un spectateur </br>
                        Envoyer un mail </br>
                        Exporter des étiquettes </br>
                    </td>
                </tr>
                <tr>
                    <td><a href=\"index.php?action=reservation\"><img src=\"image/reservation.png\"/></a></td>
                    <td>Effectuer une réservation</td>
                </tr>
                <tr>
                    <td>
                        <img src=\"image/listes.png\"/><br/>
                        <a href=\"index.php?action=rechercherReservation\"><img src='image/consulterReservation.png'</a><br/>
                        <a href=\"index.php?action=rechercherSpectateur\"><img src='image/consulterSpectateur.png'</a><br/>
                        <a href=\"index.php?action=consulterListe\"><img src='image/consulterListe.png'</a>
                    </td>
                    <td>
                        Rechercher une réservation<br/>
                        Rechercher un spectateur<br/>
                        Consulter les listes
                    </td>
                </tr>
                <tr>
                    <td><a href=\"index.php?action=extraction\"><img src=\"image/extraction.png\"/></a></td>
                    <td>Exporter la liste des réservations pour une certaine date </td>
                </tr>
                <tr>
                    <td>
                        <a href=\"index.php?action=admin\"><img src=\"image/administration.png\"/></a><br/>
                    </td>
                    <td>
                        Ajouter rang utilisateur, modifier droits des utilisateurs
                    </td>
                </tr>
        
            </table>";
    }
    else
    if($_GET["action"]=="admin"){ // page administration
        if(unserialize($_SESSION['connection'])->getLogin()=='admin'){ // si l'utilisateur a le droit d'accès
            if(isset($_GET["error"])) { // cas d'erreur
                if ($_GET["error"] == "nullError"){ //  Si l'un des champs est manquant
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Veuillez compléter tout les champs.</div>";
                }elseif($_GET["error"] == "badMdpError"){   //  Si les mots de passes ne correspondent pas
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Mot de passe incorrect.</div>";
                }elseif($_GET["error"] == "userExistError"){ //  Si l'utilisateur existe déjà.
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Cet utilisateur existe déjà.</div>";
                }elseif($_GET["error"] == "mdpNotCorrespondingError"){ //  Si le nouveau mot de passe et sa confirmation sont différents
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Les mots de passe ne correspondent pas.</div>";
                }elseif($_GET["error"] == "failError"){ //  Si l'opération a échouée
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Modification du mot de passe impossible.</div>";
                }
            }elseif(isset($_GET["success"])){ // opération réussie
                if($_GET["success"] == "user"){ //  Si l'ajout d'un utilisateur a réussi
                    echo "<div class=\"alert alert-success\" role=\"alert\">Nouvel utilisateur ajouté !</div>";
                }elseif($_GET["success"] == "mdp"){ //  Si la modification d'un mot de passe a réussie
                    echo "<div class=\"alert alert-success\" role=\"alert\">Modification du mot de passe effectuée !</div>";
                }
            }
            echo"
                <script type='text/javascript' src='js/admin.js'></script>
                <table>
                    <caption>ADMINISTRATION</caption>
                    <tr><td colspan='4'><button id=\"boutonAddRang\" class='button'>Ajouter un nouvel utilisateur</button></td></tr>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rubrique</th>
                        <th>Droit</th>
                    </tr>";

            afficherTableauAdministration();

            // formulaire ajout utilisateur
            echo "
                </table>
                <div id='modal'></div>
                <div id='formNewUser'>
                    <button class='button closeButton'>X</button>
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
                <div id='formModifierMdp'>
                    <button class='button closeButton'>X</button>
                    <form method='post' action='traitements/TraitementMdp.php'>
                        <input type='hidden' name='login' id='login' value=''/>
                        <label for='ancienMdp'>Ancien mot de passe</label>
                        <input type='password' name='ancienMdp' id='ancienMdp'/>
                        <br/><br/>
                        <label for='nouveauMdp'>Nouveau mot de passe</label>
                        <input type='password' name='nouveauMdp' id='nouveauMdp'/>
                        <br/><br/>
                        <label for='confirmerMdp'>Confirmez mot de passe</label>
                        <input type='password' name='confirmerMdp' id='confirmerMdp'/>
                        <br/><br/>
                        <input type='submit' class='button' value='Modifier le mot de passe'/>
                    </form>
                </div>
            ";
        }else{
            require_once "utils/Message.php";
        }
    }else
        if($_GET["action"]=="spectacle"){ // page spectacle
            if(unserialize($_SESSION['connection'])->getAjouterSpectacle()=='M') { //  si l'utilisateur a le droit d'accès
                echo "<h1 id=\"titreSpectacle\">AJOUTER SPECTACLE</h1>";

                if (isset($_GET["error"])) { // cas d'erreur
                    if ($_GET["error"] == "nullError") { //  Si l'un des champs est manquant
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Veuillez compléter tout les champs.</div>";

                    } elseif ($_GET["error"] == "titleExistError") { //  Si le spectacle existe déjà.
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Un spectacle portant ce nom existe déjà.</div>";
                    }
                } elseif (isset($_GET["success"])) { // ajout réussi
                    if ($_GET["success"] == "true") {
                        echo "<div class=\"alert alert-success\" role=\"alert\">Nouveau spectacle ajouté !</div>";
                    }
                }
                // formulaire ajout du spectacle
                echo "
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
            }else{
                require_once "utils/Message.php";
            }
    }else
        if($_GET["action"]=="acteur"){ // page personnel
            if(unserialize($_SESSION['connection'])->getAjouterSpectacle()=='M') { //  si l'utilisateur a le droit d'accès
                //connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                //récupérer tous les titres
                $spectacleManager = new SpectacleManager($pdo);
                $titres = $spectacleManager->getAllTitles();

                // formulaire ajout du personnel
                echo "
                        <script type='text/javascript' src='js/personnel.js'></script>
                        <h1 id=\"titreSpectacle\">AJOUTER PERSONNEL</h1>
                        <div class=\"alert alert-success\" style='display:none' role=\"alert\">Ajout réussi !</div>
                        <div class=\"alert alert-danger\" style='display:none' role=\"alert\">Erreur : champs manquants ou personnel déjà existant.</div>
                        <div class=\"formAdd content2\" id=\"acteur\">
                            <label for=\"titreSpectacle\">Spectacle: </label>
                            <select name=\"spectacle\" id='titre'>";

                foreach ($titres as $titre) {
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
                            <option></option>
                            <option value=\"acteur\">Acteur</option>
                            <option value=\"mise en scene\">Mise en scène</option>
                            <option value=\"regisseur\">Régisseur</option>
                            <option value=\"decor\">Décor</option>
                            <option value=\"aide-emoire\">Aide-mémoire</option>
                            <option value=\"presentation\">Présentation du spectacle</option>
                            <option value=\"maquillage\">maquillage</option>
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
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
    }else
        if($_GET["action"]=="representation"){  // page representation
            if(unserialize($_SESSION['connection'])->getAjouterRepresentation()=='M') { //  si l'utilisateur a le droit d'accès
                echo "<h1 id='titreRepresentation'>AJOUTER REPRESENTATION</h1>";
                if (isset($_GET["error"])) { // cas d'erreurs
                    if ($_GET["error"] == "nullError") { //  Si l'un des champs est manquant
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Veuillez compléter tout les champs.</div>";
                    } elseif ($_GET["error"] == "representationExistError") { //  Si la representation existe déjà.
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Une représentation existe déjà à cette date.</div>";
                    }
                } elseif (isset($_GET["success"])) { // ajout réussi
                    if ($_GET["success"] == "true") {
                        echo "<div class=\"alert alert-success\" role=\"alert\">Nouvelle représentation ajoutée !</div>";
                    }
                }

                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer tous les titres
                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                // formulaire ajouter représentation
                echo "
                   <div class=\"formAdd content2\" id=\"representation\">
                   <form  method='POST' action='traitements/TraitementRepresentation.php'>
                            <label for=\"titre\">Spectacle: </label>
                            <select name=\"titre\">
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
                     <input type=\"date\" name=\"date\"/>
                     <br/><br/>
                     <label for=\"heure\">Heure de représentation:</label>
                     <input type=\"time\" name=\"heure\"/>
                     <br/><br/>
                     <button id=\"addRepresentation\" value=\"ajouter representation\">Ajouter représentation</button>
                 </form>
                   </div>
                ";
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
    }else
        if($_GET["action"] == "salle"){   // page salle
            if(unserialize($_SESSION['connection'])->getSalle()=='M') { //  si l'utilisateur a le droit d'accès
                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer toutes dates de réprésentation
                $representationManager = new RepresentationManager($pdo);
                $dates = $representationManager->getAllDates();

                echo "
                    <script type='text/javascript' src='js/salle.js'></script>
                    <h1 id=\"titreSalle\">SALLE</h1>
                    <div class=\"content2 salle\">
                        <form method=\"POST\">
                        <label for=\"date\">Date de représentation:</label>
                            <select id=\"date\" name=\"date\">
                ";
                foreach ($dates as $date) {
                    echo "<option value='" . $date['date'] . "|" . $date['heure'] . "'>" . $date['date'] . " " . $date['heure'] . "</option>";
                }

                echo "
                        </select>
                        </br><br/>
                        <input id='rechercheSalle' class=\"bouton\" type=\"submit\" value=\"Rechercher salle\"/>
                    </form>
                </div>";

                if (isset($_POST["date"])) { //afficher plan de salle
                    $tabSalle = afficherPlanSalle(explode('|', $_POST["date"])[0], explode('|', $_POST["date"])[1]);

                    // formulaire copier plan de salle vers une autre date
                    echo "
                        <br/>
                        <div class=\"alert alert-danger dateInvalide\" style='display: none' role=\"alert\">Veuillez sélectionner une autre date.</div>
                        <div class=\"alert alert-success\" style='display: none' role=\"alert\">Copie effectuée !</div>
                        <div class=\"alert alert-danger copyFailed\" style='display: none' role=\"alert\">Échec lors de la copie.</div>
                        <div class=\"content2 salle\">
                            <input id='currentDate' type='hidden' value='" . $_POST['date'] . "'/>
                            <label for=\"dateCopie\">Choisir date:</label>
                            <select id='dateCopie' name=\"date\">";

                    foreach ($dates as $date) {
                        echo "<option value='" . $date['date'] . "|" . $date['heure'] . "'>" . $date['date'] . " " . $date['heure'] . "</option>";
                    }

                    echo "
                        </select>
                        <button id='copierPlanSalle' class='bouton'>Copier plan de salle vers une autre date</button>
                    </div>
                    <br/>
                    <div class='alert alert-danger chaiseNonModifiable' style='display: none' role='alert'>Échec de la modification de la chaise</div>";
                    echo $tabSalle;
                }
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
    }else
        if($_GET["action"] == "reservation"){ // page reservation
            if(unserialize($_SESSION['connection'])->getReservations()=='M') { //  si l'utilisateur a le droit d'accès
                echo "<h1>RESERVATION</h1>";

                if (isset($_GET["error"])) { // cas d'erreur
                    if ($_GET["error"] == "nullError") { //  Si l'un des champs est manquant
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Veuillez compléter tout les champs.</div>";
                    } elseif ($_GET["error"] == "spectateurExistError") { //  Si le spectateur existe déjà.
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Ajout échoué : un spectateur possédant ces informations existe déjà.</div>";
                    } elseif ($_GET["error"] == "BadFormatTelFixe"){ // si  mauvais format du téléphone fixe
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format du numéro de téléphone fixe.</div>";
                    } elseif ($_GET["error"] == "BadFormatTelMobile"){  // si  mauvais format du téléphone mobile
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format du numéro de téléphone mobile.</div>";
                    } elseif ($_GET["error"] == "BadFormatMail") {  // si  mauvais format de l'adresse mail
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format de l'adresse mail.</div>";
                    }
                } elseif (isset($_GET["success"])) {
                    if ($_GET["success"] == "true") { // ajout réussi
                        echo "<div class=\"alert alert-success\" role=\"alert\">Nouveau spectateur ajouté !</div>";
                    }
                }
                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer tous les titres
                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                // formulaire rechercher une représentation
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
                if (isset($_POST["date"])) { // représentation sélectionnée
                    $temp = explode('|', $_POST['date']);
                    $date = $temp[0];
                    $heure = $temp[1];

                    // connexion DB
                    $dbManager = new DBManager();
                    $pdo = $dbManager->connect();

                    // récupéerer tous les spectateurs
                    $spectatorManager = new SpectatorManager($pdo);
                    $tabNom = $spectatorManager->getAllSpectators();

                    // formulaire réservation
                    echo "
                        <div class=\"alert alert-success\" style='display:none' role=\"alert\">Réservation effectuée</div>
                        <div class=\"alert alert-danger zeroChaiseSelect\" style='display:none' role=\"alert\">Aucune place n'a été sélectionnée</div>
                        <div class=\"alert alert-warning limitReached\" style='display:none' role=\"alert\">Le nombre de places demandées a été atteint</div>
                        <div class=\"content3 salle reservationbis\">
                            <input type='hidden' id='titreSelect' value=\"" . $_POST['titre'] . "\"/>
                            <input type='hidden' id='dateSelect' value='" . $date . "'/>
                            <input type='hidden' id='heureSelect' value='" . $heure . "'/>
                            <label for=\"nom\">Nom de la personne :</label>
                            <input type='text' name='nom' id='nom'/>
                            <br/><br/>
                            <label for=\"prenom\">Prénom de la personne :</label>
                            <input type='text' name='prenom' id='prenom'/>
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
                     
                     <!-- formulaire ajouter spectateur -->
                    <div id='modal'></div>	
                    <div class=\"contentForm\" id=\"formNewSpectator\">
                            <button class='button closeButton'>X</button>
                            <form method=\"POST\" action=\"traitements/TraitementAjoutSpectator.php\">
                                <label for=\"nom\">Nom:</label>
                                <input type=\"text\" name=\"nom\" required/>
                                <br/><br/>
                                <label for=\"prenom\">Prénom:</label>
                                <input type=\"text\" name=\"prenom\" required/>
                                <br/><br/>
                                <label for=\"rue\">Rue:</label>
                                <input type=\"text\" name=\"adresse\" required/>
                                <br/><br/>
                                <label for=\"numero\">Numéro:</label>
                                <input type=\"text\" name=\"numero\" required/>
                                <br/><br/>
                                <label for=\"localite\">Ville:</label>
                                <input type=\"text\" name=\"localite\" required/>
                                <br/><br/>
                                <label for=\"cPostal\">Code postal:</label>
                                <input type=\"number\" name=\"cPostal\" required/>
                                <br/><br/>
                                <label for=\"noFixe\">Téléphone fixe:</label>
                                <input type=\"text\" name=\"noFixe\"/>
                                <br/><br/>
                                <label for=\"noGsm\">Téléphone mobile:</label>
                                <input type=\"text\" name=\"noGsm\"/>
                                <br/><br/>
                                <label for=\"mail\">Adresse mail:</label>
                                <input type=\"email\" name=\"mail\"/>
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

                    echo "<div class='alert alert-danger pasDisponible' style='display:none' role='alert'>Pas disponible</div>";

                    // afficher le plan de salle pour la réservation
                    echo afficherPlanSalleReservation($date);
                }
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"]=="spectateur"){ // page spectateur
            if(unserialize($_SESSION['connection'])->getModifierSpectateur()=='M') { //  si l'utilisateur a le droit d'accès
                echo "<h1 id='titreModifierSpectateur'>MODIFIER SPECTATEUR</h1>";
                if (isset($_GET["error"])) { // cas d'erreur
                    if ($_GET["error"] == "nullError") { //  Si l'un des champs est manquant
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Veuillez compléter tout les champs.</div>";
                    } elseif ($_GET["error"] == "spectateurModifierExistError") { //  Si le spectateur n'a pas été modifier.
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Ajout échoué : un spectateur possédant ce numéro de téléphone, de GSM ou cet email existe déjà</div>";
                    } elseif ($_GET["error"] == "BadFormatTelFixe") { //  Si mauvais format du téléphone fixe
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format du numéro de téléphone fixe.</div>";
                    } elseif ($_GET["error"] == "BadFormatTelMobile") {  //  Si mauvais format du téléphone mobile
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format du numéro de téléphone mobile.</div>";
                    } elseif ($_GET["error"] == "BadFormatMail") {  //  Si mauvais format de l'adresse email
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Mauvais format de l'adresse mail.</div>";
                    }
                } elseif (isset($_GET["success"])) {
                    if ($_GET["success"] == "true") { // modification réussi
                        echo "<div class=\"alert alert-success\" role=\"alert\">Spectateur modifié !</div>";
                    }
                }

                // formulaire modifier spectateur
                echo "
                   <script type='application/javascript' src='js/spectateur.js'></script>
                   <script type='application/javascript' src='js/liste.js'></script>
                        <div class=\"contentForm\" id=\"ajout\">
                            <form method=\"POST\" action=\"traitements/TraitementModifierSpectator.php?action=modifier\">
                                <label for=\"nom\">Nom:</label>
                                <input type=\"text\" name=\"nom\" id=\"nom\" autofocus=\"autofocus\" size=\"15\" required/>
                                </br> </br>
                                <label for=\"prenom\">Prénom:</label>
                                <input type=\"text\" name=\"prenom\" id=\"prenom\" size=\"15\" required/>
                                <br/><br/>
                                <label for=\"rue\">Rue:</label>
                                <input id='rue' type=\"text\" name=\"adresse\" required/>
                                <br/><br/>
                                <label for=\"numero\">Numéro:</label>
                                <input id='numero' type=\"text\" name=\"numero\" required/>
                                <br/><br/>
                                <label for=\"localite\">Ville:</label>
                                <input id='localite' type=\"text\" name=\"localite\" required/>
                                <br/><br/>
                                <label for=\"cPostal\">Code postal:</label>
                                <input id='cPostal' type=\"number\" name=\"cPostal\" required/>
                                <br/><br/>
                                <label for=\"noFixe\">Téléphone fixe:</label>
                                <input id='noFixe' type=\"text\" name=\"noFixe\"/>
                                <br/><br/>
                                <label for=\"noGsm\">Téléphone mobile:</label>
                                <input id='noGsm' type=\"text\" name=\"noGsm\"/>
                                <br/><br/>
                                <label for=\"mail\">Adresse mail:</label>
                                <input id='mail' type=\"email\" name=\"mail\"/>
                                <br/><br/>
                                <label for=\"commentaire\">Commentaire:</label>
                                <textarea id='commentaire' name=\"commentaire\"></textarea>
                                <br/>
                                <input id=\"bouton\" type=\"submit\" value=\"modifier spectateur\"/>
                            </form>
                        </div>
                        ";
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"]=="rechercherReservation"){ // consulter réservation
            if(unserialize($_SESSION['connection'])->getRechercherReservation()=='M') { //  si l'utilisateur a le droit d'accès

                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer tous les titres
                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                // formulaire rechercher une réservation
                echo "
                     <script type='text/javascript' src='js/liste.js'></script>
                    <h1 id=\"titreListe\">CONSULTER RESERVATION</h1>
                    <div class=\"content3\" id=\"formSpectateur\">
                    <label>Titre du spectacle:</label>
                        <select id='titre' name=\"titre\"> 
                        <option></option>";

                foreach ($tabTitres as $titres) {
                    echo "
                        <option value=\"$titres\">$titres</option>";
                }

                echo "
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
                    <br/>
                    <div class='alert alert-danger' style='display:none'>Aucun résultat ne correspond aux critères de recherche</div>
                    <div id='afficheReservation'></div>
                ";
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"] == "rechercherSpectateur"){ // consulter spectateur
            if(unserialize($_SESSION['connection'])->getRechercherSpectateur()=='M') { //  si l'utilisateur a le droit d'accès
                //  formulaire rechercher un spectateur
                echo "
                    <h1 id=\"titreListe\">CONSULTER SPECTATEUR</h1>
                    <script src='js/liste.js'></script>
                        <div class=\"content2\" id=\"formSpectateur\">
                            <label for=\"nom\">Nom de la personne:</label>
                            <input type=\"text\" name=\"nom\" id=\"nom\" autofocus=\"autofocus\" size=\"15\"/>
                            </br></br>
                            <label for=\"nom\">Prénom de la personne:</label>
                            <input type=\"text\" name=\"prenom\" id=\"prenom\" autofocus=\"autofocus\" size=\"15\"/>
                            </br>
                            <input id=\"rechercherSpectateur\" type=\"submit\" value=\"Rechercher spectateur\"/>
                        </div>
                    <br/>
                    <div class='alert alert-danger' style='display:none'>Aucun résultat ne correspond aux critères de recherche</div>
                    <div id='afficherSpectateur'></div>
                ";
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"]=="consulterListe"){  // consulter une liste
            if(unserialize($_SESSION['connection'])->getConsulterListe()=='M') { //  si l'utilisateur a le droit d'accès
                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer tout les titres
                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                // formulaire consulter liste
                echo "
                    <h1 id=\"titreListe2\">CONSULTER LISTE</h1>
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
                echo "
                            </select>
                            <br/><br/>
                            <label for=\"date\">Date de représentation:</label>
                            <select id='date' name=\"date\">
                            </select>
                        </div>
                        <br/>
                        <input id=\"consulterListe\" class='button' type=\"submit\" value=\"Consulter liste\"/>
                    </div>
                    <br/>
                    <div class='alert alert-danger pasTrouve' style='display:none'>Aucun résultat ne correspond aux critères de recherche</div>
                    <div class='alert alert-danger pasSupprime' style='display:none'>La suppression n'a pas pu être effectuée</div>
                    <div class='alert alert-success' style='display:none'>Suppression effectuée</div>
                    <div id='afficherListe'></div>
                ";
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"] == "extraction"){ // page extraction
            if(unserialize($_SESSION['connection'])->getExtraction()=='M') { //  si l'utilisateur a le droit d'accès

                // connexion DB
                $dbManager = new DBManager();
                $pdo = $dbManager->connect();

                // récupérer tous les titres
                $spectacleManager = new SpectacleManager($pdo);
                $tabTitres = $spectacleManager->getAllTitles();

                // formulaire extraire
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
                echo "
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
                <div id='modal'></div>
                <img src='image/chargement.gif' id='chargement'/>
                ";
                $dbManager->disconnect();
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"] == "etiquette"){ // page etiquette
            if(unserialize($_SESSION['connection'])->getExporterEtiquettes()=='M') {  // si l'utilisateur a le droit d'accès
                // formulaire etiquette
                echo "
                    <h1 id='titreEtiquettes'>EXPORTER ETIQUETTES</h1>
                    <script src='js/extraction.js'></script>
                        <div class=\"contentMail\" id=\"etiquette\">
                            <label for=\"mail\" id='ignoreWidth'>Inclure spectateurs ayant une adresse mail:</label>
                            <input  id=\"mailEtiquette\" type=\"checkbox\" name=\"mail\" value=\"1\" checked=\"checked\"> 
                            </br></br>
                            
                            <input class=\"button\" id='extraireEtiquettes' type=\"submit\" value=\"Exporter etiquettes\"/>
                            </br></br>
                            <a target='_blank' class='button' href='' id='lien' style='display:none'>Télécharger le document</a>
                        </div>
                        <div id='modal'></div>
                        <img src='image/chargement.gif' id='chargement'/>
                ";
            }else{
                require_once "utils/Message.php";
            }
        }else
        if($_GET["action"] == "mail"){ // page mail
            if(unserialize($_SESSION['connection'])->getEnvoyerMail()=='M') { //  si l'utilisateur a le droit d'accès
                // formulaire envoyer mail
                echo "
                    <h1 id='titreMail'>ENVOYER MAIL</h1>
                    <script type='text/javascript' src='js/upload.js'></script>
                    <script type='text/javascript' src='js/mail.js'></script>
                    <div class=\"contentMail\" id=\"mail\">
                            <label for='message'>Message : </label>
                            <textarea name='message' id='message'></textarea>
                            <br/><br/>
                            <label for=\"fichier\">Pièce jointe/affiche:</label> 
                            <input type=\"file\" name=\"fichier\" id='file' multiple/>
                            <input id=\"bouton\" type=\"submit\" value=\"Envoyer\"/>
                        <br/><br/>
                        <progress value='0' id='progress' style='display:none'></progress>
                    </div>
                    <br/><br/>
                    <div class='alert alert-warning' style='display:none'>Upload des fichiers en cours, veuillez patienter ...</div>
                    <div class='alert alert-success' style='display:none'>Upload des fichiers réussi !</div>
                    <div class='alert alert-danger fail' style='display:none'>Upload des fichiers échoué</div>
                    <div class='alert alert-danger noXHR' style='display:none'>Erreur : votre navigateur n'est pas compatible</div>
                    <div class='alert alert-danger fileSize' style='display:none'>Erreur : la taille totale des fichiers ne doit pas dépasser 8MO</div>
                ";
            }else{
                require_once "utils/Message.php";
            }
        }
}



echo '
    <div class="footer">
        <footer>Copyright © 2018 HELHA. All rights reserved. Art et Récréation</footer>
    </div>
</div>
</body>
</html>';

