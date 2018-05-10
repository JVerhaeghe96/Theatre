<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 23-03-18
 * Time: 20:41
 */

class Spectacle
{

    private $titre;
    private $resume;

    /**
     * Spectacle constructor.
     * @param $data array
     */
    function __construct($data)
    {
        $this->fillObject($data);
    }

    /**
     * @return String
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param String $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return String
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param String $resume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
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