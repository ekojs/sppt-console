<?php namespace Tests\Library;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use EkoJunaidiSalam\App\Command\HelperCommand;
use EkoJunaidiSalam\App\Library\DbUtil;

class LibraryDbUtilTest extends TestCase {
    protected static $app;
    public static $dbUtil;

    public static function setUpBeforeClass(): void {
        self::$app = new Application();
        self::$dbUtil = DbUtil::getInstance();
    }
    
    public function testInstanceOfDbUtil(){
        self::$dbUtil->setDebug(false);
        self::$dbUtil->setTrx(false);
        
        self::$dbUtil->setBulk(false);
        self::$dbUtil->setBind();

        $this->assertInstanceOf('EkoJunaidiSalam\App\Library\DbUtil', self::$dbUtil);
        $this->assertInstanceOf('ADODB_postgres9', self::$dbUtil->getConn());
        $this->assertFalse(self::$dbUtil->getTrx());
    }

    public function testDbUtilTransaction(){
        self::$dbUtil->setTrx(true);
        self::$dbUtil->setBind(array("01.01"));
        $res = self::$dbUtil->query("select * from tb_mapping where param=?",["01.01"]);
        $this->assertIsArray($res->fetchRow());
    }

    public function testDbUtilException(){
        $this->expectException("Exception");
        $this->expectErrorMessageMatches('/postgres9 error/');
        $res = self::$dbUtil->query("select * from tb_mappings");
        $this->assertStringContainsString("postgres9 error",$res);
    }
}