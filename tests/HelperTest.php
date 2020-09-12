<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase{
	
	public function testSum(): void {
		//require_once "src/Foo.php";
		$this->assertEquals("Hello ekojs",callMe("ekojs"));
	}
}
