<?php namespace Semaphore\Tests;

use Semaphore\Semaphore;

class BasicTest extends TestCase
{
	public function testLock() {
		$semaphore = new Semaphore();
        $key = uniqid();
		$this->assertTrue($semaphore->lock($key));
		$this->assertFalse($semaphore->lock($key));
		$this->assertTrue($semaphore->unlock($key));
	}
}
