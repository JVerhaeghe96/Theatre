<?php
/**
 * Created by IntelliJ IDEA.
 * User: Marine
 * Date: 29/04/2018
 * Time: 15:34
 */

require_once "../../../../autoload.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract class TestSpectacle extends TestCase
{
    use TestCaseTrait;

    public function getDataSet()
    {
        return $this->createXMLDataSet('spectacle.xml');
    }

}