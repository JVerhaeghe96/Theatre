<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 24-03-18
 * Time: 09:50
 */

class Personnage extends PO
{
    private $titre;
    private $nom;
    private $prenom;
    private $persNom;
    private $persPrenom;

    /**
     * Personnage constructor.
     * @param $data array
     */
    function __construct($data)
    {
        $this->fillObject($data);
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getPersNom()
    {
        return $this->persNom;
    }

    /**
     * @param mixed $persNom
     */
    public function setPersNom($persNom)
    {
        $this->persNom = $persNom;
    }

    /**
     * @return mixed
     */
    public function getPersPrenom()
    {
        return $this->persPrenom;
    }

    /**
     * @param mixed $persPrenom
     */
    public function setPersPrenom($persPrenom)
    {
        $this->persPrenom = $persPrenom;
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