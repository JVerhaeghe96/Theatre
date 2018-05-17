<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 29/04/2018
 * Time: 15:53
 */
require_once "../../../../autoload.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract  class TestDataSetAssertionSpectacle extends TestCase
{
    use TestCaseTrait;

    /**
     * @test
     */
    public function testAddEntry()
    {
        $this->assertSame('A', $this->getConnection()->getRowCount('spectacle'), "Pre-Condition");

        $spectacle= new Spectacle();
        $spectacle->addEntry("A", "A");

        $this->assertSame(1, $this->getConnection()->getRowCount('spectacle'), "test");
    }

}