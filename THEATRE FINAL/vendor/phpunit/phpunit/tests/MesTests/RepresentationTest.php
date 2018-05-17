<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 01-05-18
 * Time: 13:33
 */

namespace vendor\phpunit\phpunit\tests\MesTests;

require_once "../../../../../utils/DateUtils.php";
require_once "../../../../../model/Spectacle.php";
require_once "../../../../../model/Representation.php";
require_once "../../../../../manager/AbstractManager.php";
require_once "../../../../../manager/DBManager.php";
require_once "../../../../../manager/SpectacleManager.php";
require_once "../../../../../manager/RepresentationManager.php";


use PHPUnit\Framework\TestCase;
use manager;
use model;

class RepresentationTest extends TestCase
{
    /**
     * @var $spectacleManager manager\SpectacleManager
     */
    private $spectacleManager;
    /**
     * @var $representationManager manager\RepresentationManager
     */
    private $representationManager;
    /**
     * @var model\Spectacle
     */
    private $spectacle1;
    /**
     * @var model\Representation
     */
    private $representation1;
    /**
     * @var model\Representation
     */
    private $representation2;
    /**
     * @var model\Representation
     */
    private $representation3;

    /**
     * @before
     */
    public function init(){
        $dbManager = new manager\DBManager();
        $pdo = $dbManager->connect();
        $this->spectacleManager = new manager\SpectacleManager($pdo);
        $this->representationManager = new manager\RepresentationManager($pdo);

        $this->spectacle1 = new model\Spectacle(array("titre" => "Julia", "resume" => "Une tragique et terrifiante histoire ..."));
        $this->representation1 = new model\Representation(array("titre" => "Julia", "date" => "2018-05-10", "heure" => "19:30"));
        $this->representation2 = new model\Representation(array("titre" => "Julia", "date" => "2018-05-11", "heure" => "19:30"));
        $this->representation3 = new model\Representation(array("titre" => "Julia", "date" => "2018-05-12", "heure" => "15:30"));

        $this->spectacleManager->ajouterSpectacle($this->spectacle1);
    }

    /**
     * @test
     */
    public function testAjouterRepresentation(){
        self::assertTrue($this->representationManager->ajouterRepresentation($this->representation1));
        self::assertTrue($this->representationManager->ajouterRepresentation($this->representation2));
        self::assertTrue($this->representationManager->ajouterRepresentation($this->representation3));
    }

    /**
     * @test
     * @depends testAjouterRepresentation
     */
    public function testListerDates(){
        $listeDates = $this->representationManager->getAllDates();

        self::assertTrue($listeDates[0]["date"] == "2018-05-10" && $listeDates[0]["heure"] == "19:30:00");
        self::assertTrue($listeDates[1]["date"] == "2018-05-11" && $listeDates[1]["heure"] == "19:30:00");
        self::assertTrue($listeDates[2]["date"] == "2018-05-12" && $listeDates[2]["heure"] == "15:30:00");
    }
}
