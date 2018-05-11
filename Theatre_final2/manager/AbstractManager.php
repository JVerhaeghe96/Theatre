<?php

namespace app\manager;

abstract class AbstractManager{
    /**
     * @var \PDO $db
     * @var \PDO $db_archive
     */
	protected $db;
	protected $db_archive;
}

?>