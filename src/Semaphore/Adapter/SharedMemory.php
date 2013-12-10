<?php namespace Semaphore\Adapter;

use RuntimeException;
use Shared\Storage;

class SharedMemory implements AdapterInterface
{
	/**
	 * Key for semaphores counter in shm
	 * @const string
	 */
	const K_SEM_COUNT = 'semaphores count';

	/**
 	 * Shared memory storage
 	 * @var Shared\Storage
	 */
	protected $shm;

	/**
	 * Default shm key, unique enough
	 * @var string
	 */
	const KEY = 'very_unique_shared_memory_key_really...';

	/**
	 * Create shared memory adapter with given key
	 * @param string $key
	 */
	public function __construct($key = self::KEY) {
		$this->key = $key;
		$this->setupStorage();
	}

	/**
	 * Destroy shared storage if there is 0 semaphores out there
	 */
	public function __destruct() {
		if (0 == $this->count()) {
			$this->shm->destroy();
		}
	}

	/**
	 * Lock semaphore with given key
	 * @param string $key
	 * @return bool
	 */
	public function lock($key) {
		if ($this->locked($key)) {
			return false;
		}

		return $this->_lock($key, true);
	}

	/**
	 * Unlock semaphore with given key
	 * @param string $key
	 * @return bool
	 */
	public function unlock($key) {
		if (!$this->locked($key)) {
			return false;
		}

		return $this->_unlock($key);
	}

	/**
	 * Check if semaphore with given key are locked
	 * @param string $key
	 * @return bool
	 */
	public function locked($key) {
		return $this->shm->has($key);
	}

	public function count() {
		return $this->shm->get(self::K_SEM_COUNT);
	}

	/**
	 * Create semaphore key from storage
	 * @param string $key
	 * @return bool
	 */
	protected function _lock($key) {
		$result = $this->shm->set($key, true);

		if ($result) {
			$this->incCounter();
		}

		return $result;
	}

	/**
	 * Remove semaphore key from storage
	 * @param string $key
	 * @return bool
	 */
	protected function _unlock($key) {
		$result = $this->shm->unset($key);

		if ($result) {
			$this->decCounter();
		}

		return $result;
	}

	/**
	 * Increment semaphores counter
	 * @return int
	 */
	protected function incCounter() {
		$counter = $this->shm->get(self::K_SEM_COUNT);
		return $this->shm->set(self::K_SEM_COUNT, $counter + 1);
	}

	/**
	 * Decrement semaphores counter
	 * @return int
	 */
	protected function decCounter() {
		$counter = $this->shm->get(self::K_SEM_COUNT);
		return $this->shm->set(self::K_SEM_COUNT, $counter - 1);
	}

	/**
	 * Setup shared memory storage
	 */
	protected function setupStorage() {
		$this->shm = new Storage($this->key);
		$count = $this->shm->get(self::K_SEM_COUNT);

		if (is_null($count)) {
			$this->initEmptyStorage();
		}
	}

	/**
	 * Initialize storage with empty values
	 */
	protected function initEmptyStorage() {
		$this->shm->set(self::K_SEM_COUNT, 0);
	}
}
