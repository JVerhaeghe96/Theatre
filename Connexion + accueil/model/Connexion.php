<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 10-03-18
 * Time: 11:27
 */

class Connexion extends PO
{
    private $login;
    private $mdp;
    private $droits;

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
    public function getDroits()
    {
        return $this->droits;
    }

    /**
     * @param String $droits
     */
    public function setDroits($droits)
    {
        //  Les droits sont stockés dans la DB sous le format JSON, par conséquent on décode pour le mettre dans une variable
        $this->droits = json_decode($droits);
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
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param String $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
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

    function toString(){
        return "login : ". $this->login ."<br/>droits : ". $this->droits;
    }
}