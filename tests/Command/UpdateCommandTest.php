<?php namespace Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use EkoJunaidiSalam\App\Command\UpdateCommand;

class UpdateCommandTest extends TestCase {
    protected static $app;

    public static function setUpBeforeClass(): void {
        self::$app = new Application();
        self::$app->add(new UpdateCommand());
    }
    
    public function testUpdateFunction(){
        $cmd = self::$app->find('update');
        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute([]);
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Proses update dibatalkan.', $output);
    }
    
    public function testUpdateAllTable(){
        $cmd = self::$app->find('update');
        $cmdTester = new CommandTester($cmd);
        $cmdTester->setInputs(['yes']);
        $cmdTester->execute([]);
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Seluruh tabel telah di update.', $output);
    }
    
    public function testUpdateSpecificTable(){
        $cmd = self::$app->find('update');
        $cmdTester = new CommandTester($cmd);
        $cmdTester->setInputs(['yes']);
        $cmdTester->execute([
            'table' => 'p16'
        ]);
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Tabel p16 telah di update.', $output);

        $cmdTester->setInputs(['no']);
        $cmdTester->execute([
            'table' => 'p16'
        ]);
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Proses update dibatalkan.', $output);
    }
}