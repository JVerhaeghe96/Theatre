<?php

namespace app\manager;

class DBManager extends AbstractManager{

    /**
     * @return \PDO|null
     */
	function connect(){
		$this->db = null;
		try {
			$strConnection = 'mysql:host=localhost;dbname=theatre;charset=utf8';
			$this->db = new \PDO ($strConnection, 'root', '');
			$this->db->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch ( \PDOException $e ) {
            $this->db = null;
		}
		return $this->db;
	}

    /**
     * @return null|\PDO
     */
	function connect_archive(){
	    $this->db_archive = null;
	    try{
	        $strConnection = 'mysql:host=localhost;dbname=theatre_archive;charset=utf8';
	        $this->db_archive = new \PDO($strConnection, 'root', '');
	        $this->db_archive->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
	        $this->db_archive = null;
        }
        return $this->db_archive;
    }
	
	function disconnect(){
		$this->db = null;
	}

    function disconnect_archive(){
        $this->db_archive = null;
    }
}