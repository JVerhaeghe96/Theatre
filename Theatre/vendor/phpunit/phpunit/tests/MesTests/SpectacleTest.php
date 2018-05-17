<?php
/**
 * Created by IntelliJ IDEA.
 * User: Julien
 * Date: 30-04-18
 * Time: 21:32
 */

namespace vendor\phpunit\phpunit\tests\MesTests;

require_once "../../../../../model/Spectacle.php";
require_once "../../../../../manager/AbstractManager.php";
require_once "../../../../../manager/DBManager.php";
require_once "../../../../../manager/SpectacleManager.php";

use model;
use manager;
use PHPUnit\Framework\TestCase;


class SpectacleTest extends TestCase
{
    /**
     * @var $spectacleManager manager\SpectacleManager
     */
    private $spectacleManager;
    /**
     * @var model\Spectacle
     */
    private $spectacle1;
    /**
     * @var model\Spectacle
     */
    private $spectacle2;
    /**
     * @var model\Spectacle
     */
    private $spectacle3;
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @before testAjouterSpectacle
     */
    public function init(){
        $dbManager = new manager\DBManager();
        $this->pdo = $dbManager->connect();
        $this->spectacleManager = new manager\SpectacleManager($this->pdo);

        $this->spectacle1 = new model\Spectacle(array("titre" => "Roméo et Juliette", "resume" => "Une tragique histoire d'amour ..."));
        $this->spectacle2 = new model\Spectacle(array("titre" => "Hamlett", "resume" => ""));
        $this->spectacle3 = new model\Spectacle(array("titre" => "Albert au jardinet", "resume" => ""));
    }

    /**
     * @test
     */
    public function testAjouterSpectacle(){

        $this->assertTrue($this->spectacle1->getTitre() == "Roméo et Juliette");
        $this->assertTrue($this->spectacleManager->ajouterSpectacle($this->spectacle1));

        $this->assertFalse($this->spectacleManager->ajouterSpectacle($this->spectacle1));


        $this->assertTrue($this->spectacle2->getTitre() == "Hamlett");
        $this->assertTrue($this->spectacleManager->ajouterSpectacle($this->spectacle2));


        $this->assertTrue($this->spectacle3->getTitre() == "Albert au jardinet");
        $this->assertTrue($this->spectacleManager->ajouterSpectacle($this->spectacle3));

        $this->assertFalse($this->spectacleManager->ajouterSpectacle($this->spectacle3));
    }

    /**
     * @depends testAjouterSpectacle
     */
    public function testListerTitres(){
        $listeTitre = $this->spectacleManager->getAllTitles();

        $this->assertTrue($listeTitre[0] == "Albert au jardinet");
        $this->assertTrue($listeTitre[1] == "Hamlett");
        $this->assertTrue($listeTitre[2] == "Roméo et Juliette");
    }

    /**
     * @depends testListerTitres
     */
    public function testSupprimerTitres(){
        self::assertTrue($this->spectacleManager->deleteSpectacle($this->spectacle1->getTitre()));
        self::assertTrue($this->spectacleManager->deleteSpectacle($this->spectacle2->getTitre()));
        self::assertTrue($this->spectacleManager->deleteSpectacle($this->spectacle3->getTitre()));
    }

}
