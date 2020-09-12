<?php namespace Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use EkoJunaidiSalam\App\Command\HelperCommand;

class HelperCommandTest extends TestCase {

    public function testHelper(){
        $app = new Application();
		$app->add(new HelperCommand());
        $cmd = $app->find('helper');
        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute([
            'type' => 'mapper',
		]);
		
        $output = $cmdTester->getDisplay();
        $this->assertStringContainsString('Helper Mapper', $output);
    }
}