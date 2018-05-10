<?php

class DBManager extends AbstractManager{

    /**
     * @return PDO
     */
	function connect(){
		$this->db = null;
		try {
			$strConnection = 'mysql:host=localhost;dbname=theatre';
			$this->db = new PDO ($strConnection, 'root', '');
			$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			$msg = 'ERREUR PDO dans ' . $e->getFile () . ' Ligne : ' . $e->getLine () . ' : ' . $e->getMessage ();
			die ( $msg );
		}
		return $this->db;
	}
	
	function disconnect(){
		$this->db = null;
	}
}
?>