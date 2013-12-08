## Keep your thread locked!

This package provide simple semaphore wrapper which can work different adapters

### Basic usage

```php
<?php

use Semaphore\Semaphore;

$lock = new Semaphore();
$key  = 'oh no!';

if ($lock->locked($key) {
	// Meh, it's so locked...
} else {
	// Lock semaphore
	$lock->lock($key);

	// Do thread-safe operations
	reallyImportantCriticalStuff();

	// Release lock
	$lock->unlock($key);
}
```
