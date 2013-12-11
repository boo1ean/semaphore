<?php require 'vendor/autoload.php';

use Semaphore\Semaphore;

$s = new Semaphore();
var_dump($s->lock('1'));
var_dump($s->lock('1'));
var_dump($s->locked('1'));
var_dump($s->unlock('1'));
var_dump($s->unlock('1'));
