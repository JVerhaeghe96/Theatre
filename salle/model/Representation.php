<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 23/03/2018
 * Time: 20:55
 */

class Representation extends PO
{
    private $date;
    private $nbrLigne;
    private $nbrColonne;
    private $titre;

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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getNbrLigne()
    {
        return $this->nbrLigne;
    }

    /**
     * @param mixed $nbrLigne
     */
    public function setNbrLigne($nbrLigne)
    {
        $this->nbrLigne = $nbrLigne;
    }

    /**
     * @return mixed
     */
    public function getNbrColonne()
    {
        return $this->nbrColonne;
    }

    /**
     * @param mixed $nbrColonne
     */
    public function setNbrColonne($nbrColonne)
    {
        $this->nbrColonne = $nbrColonne;
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