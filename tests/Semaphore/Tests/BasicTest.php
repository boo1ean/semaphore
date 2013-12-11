<?php namespace Semaphore\Tests;

use Semaphore\Semaphore;

class BasicTest extends TestCase
{
	public function testLock() {
		$semaphore = new Semaphore();
		$lock = $semaphore->lock('a');
		var_dump($lock);
		$semaphore->getAdapter()->destroy();
	}
}
