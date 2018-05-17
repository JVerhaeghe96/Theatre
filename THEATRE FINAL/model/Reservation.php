<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 26/03/2018
 * Time: 16:03
 */

namespace app\model;

class Reservation
{
    private $id;
    private $nbSieges;
    private $remarque;
    private $SpecId;
    private $titre;

    /**
     * @param $data array
     */
    public function __construct($data)
    {
        $this->fillObject($data);
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
    public function getNbSieges()
    {
        return $this->nbSieges;
    }

    /**
     * @param mixed $nbSieges
     */
    public function setNbSieges($nbSieges)
    {
        $this->nbSieges = $nbSieges;
    }

    /**
     * @return mixed
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * @param mixed $remarque
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;
    }

    /**
     * @return mixed
     */
    public function getSpecId()
    {
        return $this->SpecId;
    }

    /**
     * @param mixed $SpecId
     */
    public function setSpecId($SpecId)
    {
        $this->SpecId = $SpecId;
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