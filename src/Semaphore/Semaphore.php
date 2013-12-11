<?php namespace Semaphore;

use Adapter\AdapterInterface;
use Semaphore\Adapter\SharedMemory;
use LogicException;

/**
 * Semaphore wrapper
 * @class
 */
class Semaphore
{
	/**
	 * List of active keys (created in scope of current object) due to unlock everything for the rescue
	 * @var array
	 */
	protected $keys = array();

	/**
	 * Automaticall unlock all semaphores locked by the given semaphore object
	 * @var bool
	 */
	protected $autoUnlock = true;

	/**
	 * Create semaphore instance with given adapter
	 * If adapter is not given - use default one
	 * @param AdapterInterface $adapter
	 */
	public function __construct(AdapterInterface $adapter = null) {
		$this->adapter = $adapter ?: $this->getDefaultAdapter();
	}

	/**
	 * Set autounlock flag
	 * @param bool
	 */
	public function setAutoUnlock($autoUnlock) {
		$this->autoUnlock = $this->autoUnlock;
	}


	/**
	 * Unlock all previously locked semaphores
	 */
	public function __destruct() {
		if ($this->autoUnlock) {
			$this->unlockAll();
		}
	}

	/**
	 * Unlock all previously locked semaphores
	 */
	public function unlockAll() {
		foreach ($this->keys as $key) {
			$this->unlock($key);
		}
	}

	/**
	 * Attempt to lock semaphore with given key
	 * @param string $key
	 * @return true if successfully locked semaphore, otherwise false
	 */
	public function lock($key) {
		$locked = $this->adapter->lock($key);

		if ($locked) {
			$this->storeKey($key);
		}

		return $locked;
	}

	/**
	 * Unlock semaphore with given key
	 * @param string $key
	 * @return bool true if successfully unlocked, otherwise false
	 */
	public function unlock($key) {
		$unlocked = $this->adapter->unlock($key);

		if ($unlocked) {
			$this->destroyKey($key);
		}

		return $unlocked;
	}

	/**
	 * Check if semaphore with given key is locked
	 * @param string $key
	 * @return bool
	 */
	public function locked($key) {
		return $this->adapter->locked($locked);
	}

	/**
	 * Get semaphore adapter
	 * @return Adapter\AdapterInterface
	 */
	public function getAdapter() {
		return $this->adapter;
	}

	/**
	 * Store key to active keys list
	 * @param string $key
	 */
	protected function storeKey($key) {
		if (empty($this->keys)) {
			$this->keys[$key] = true;
		}
	}

	/**
	 * Remove key from active key list
	 * @param string $key
	 */
	protected function destroyKey($key) {
		if (!empty($this->keys[$key])) {
			unset($this->keys[$key]);
		}
	}

	protected function getDefaultAdapter() {
		return new SharedMemory();
	}
}
