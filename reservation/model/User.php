<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 10-03-18
 * Time: 11:27
 */

class User extends PO
{
    private $login;
    private $password;
	private $salle;
	private $ajouterSpectacle;
	private $ajouterPersonnel;
	private $ajouterRepresentation;
	private $exporterToutesBoites;
	private $modifierSpectateur;
	private $envoyerMail;
	private $exporterEtiquettes;
	private $reservations;
	private $ajouterSpectateur;
	private $rechercherReservation;
	private $rechercherSpectateur;
	private $consulterListe;
	private $extraction;

    /**
     * Connexion constructor.
     * @param $data array
     */
    public function __construct($data)
    {
        $this->fillObject($data);
    }

    /**
     * @return mixed
     */
    public function getSalle()
    {
        return $this->salle;
    }

    /**
     * @param mixed $salle
     */
    public function setSalle($salle)
    {
        $this->salle = $salle;
    }

    /**
     * @return mixed
     */
    public function getAjouterSpectacle()
    {
        return $this->ajouterSpectacle;
    }

    /**
     * @param mixed $ajouterSpectacle
     */
    public function setAjouterSpectacle($ajouterSpectacle)
    {
        $this->ajouterSpectacle = $ajouterSpectacle;
    }

    /**
     * @return mixed
     */
    public function getAjouterPersonnel()
    {
        return $this->ajouterPersonnel;
    }

    /**
     * @param mixed $ajouterPersonnel
     */
    public function setAjouterPersonnel($ajouterPersonnel)
    {
        $this->ajouterPersonnel = $ajouterPersonnel;
    }

    /**
     * @return mixed
     */
    public function getAjouterRepresentation()
    {
        return $this->ajouterRepresentation;
    }

    /**
     * @param mixed $ajouterRepresentation
     */
    public function setAjouterRepresentation($ajouterRepresentation)
    {
        $this->ajouterRepresentation = $ajouterRepresentation;
    }

    /**
     * @return mixed
     */
    public function getExporterToutesBoites()
    {
        return $this->exporterToutesBoites;
    }

    /**
     * @param mixed $exporterToutesBoites
     */
    public function setExporterToutesBoites($exporterToutesBoites)
    {
        $this->exporterToutesBoites = $exporterToutesBoites;
    }

    /**
     * @return mixed
     */
    public function getModifierSpectateur()
    {
        return $this->modifierSpectateur;
    }

    /**
     * @param mixed $modifierSpectateur
     */
    public function setModifierSpectateur($modifierSpectateur)
    {
        $this->modifierSpectateur = $modifierSpectateur;
    }

    /**
     * @return mixed
     */
    public function getEnvoyerMail()
    {
        return $this->envoyerMail;
    }

    /**
     * @param mixed $envoyerMail
     */
    public function setEnvoyerMail($envoyerMail)
    {
        $this->envoyerMail = $envoyerMail;
    }

    /**
     * @return mixed
     */
    public function getExporterEtiquettes()
    {
        return $this->exporterEtiquettes;
    }

    /**
     * @param mixed $exporterEtiquettes
     */
    public function setExporterEtiquettes($exporterEtiquettes)
    {
        $this->exporterEtiquettes = $exporterEtiquettes;
    }

    /**
     * @return mixed
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * @param mixed $reservations
     */
    public function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }

    /**
     * @return mixed
     */
    public function getAjouterSpectateur()
    {
        return $this->ajouterSpectateur;
    }

    /**
     * @param mixed $ajouterSpectateur
     */
    public function setAjouterSpectateur($ajouterSpectateur)
    {
        $this->ajouterSpectateur = $ajouterSpectateur;
    }

    /**
     * @return mixed
     */
    public function getRechercherReservation()
    {
        return $this->rechercherReservation;
    }

    /**
     * @param mixed $rechercherReservation
     */
    public function setRechercherReservation($rechercherReservation)
    {
        $this->rechercherReservation = $rechercherReservation;
    }

    /**
     * @return mixed
     */
    public function getRechercherSpectateur()
    {
        return $this->rechercherSpectateur;
    }

    /**
     * @param mixed $rechercherSpectateur
     */
    public function setRechercherSpectateur($rechercherSpectateur)
    {
        $this->rechercherSpectateur = $rechercherSpectateur;
    }

    /**
     * @return mixed
     */
    public function getConsulterListe()
    {
        return $this->consulterListe;
    }

    /**
     * @param mixed $consulterListe
     */
    public function setConsulterListe($consulterListe)
    {
        $this->consulterListe = $consulterListe;
    }

    /**
     * @return mixed
     */
    public function getExtraction()
    {
        return $this->extraction;
    }

    /**
     * @param mixed $extraction
     */
    public function setExtraction($extraction)
    {
        $this->extraction = $extraction;
    }



    /**
     * @return String
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param String $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return String
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param String $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @param array $data
     */
    function fillObject(array $data)
    {
        foreach($data as $key => $value){
            $method = 'set'.ucfirst($key);
            if(method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }
}