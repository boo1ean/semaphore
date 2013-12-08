<?php namespace Semaphore\Adapter;

/**
 * Common semaphore adapter interface
 * @interface
 */
interface AdapterInterface
{
	/**
	 * Lock semaphore with given key
	 * @param string $key
	 * @return bool
	 */
	public function lock($key);

	/**
	 * Unlock semaphore with given key
	 * @param string $key
	 * @return bool
	 */
	public function unlock($key);

	/**
	 * Check if semaphore with given key are locked
	 * @param string $key
	 * @return bool
	 */
	public function locked($key);
}
