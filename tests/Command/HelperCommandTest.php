<?php namespace Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use EkoJunaidiSalam\App\Command\HelperCommand;

class HelperCommandTest extends TestCase {
    protected static $app;

    public static function setUpBeforeClass(): void {
        self::$app = new Application();
        self::$app->add(new HelperCommand());
    }
    
    public function testHelperFunction(){
        $cmd = self::$app->find('helper');
        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute([
            'type' => 'mapper',
		]);
		
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Helper Mapper', $output);

        $cmdTester->execute([
            'type' => 'list',
            'object' => 'mapping',
            'filter' => '24.01',
		]);
		
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('kota kupang', $output);
    }
}