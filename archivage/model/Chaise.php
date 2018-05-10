<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 24-03-18
 * Time: 15:32
 */

class Chaise
{
    private $tri;
    private $id;
    private $date;
    private $heure;
    private $etat;
    private $ResId;

    /**
     * Chaise constructor.
     * @param $data array
     */
    function __construct($data)
    {
        $this->fillObject($data);
    }

    /**
     * @return mixed
     */
    public function getTri()
    {
        return $this->tri;
    }

    /**
     * @param mixed $tri
     */
    public function setTri($tri)
    {
        $this->tri = $tri;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getResId()
    {
        return $this->ResId;
    }

    /**
     * @param mixed $ResId
     */
    public function setResId($ResId)
    {
        $this->ResId = $ResId;
    }

    /**
     * @param $chaise Chaise
     * @return boolean
     */
    function equals($chaise){
        if($chaise->getId() == $this->id)
            return true;

        return false;
    }

    /**
     * @return mixed
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * @param mixed $heure
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;
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