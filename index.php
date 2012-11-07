<?php
require_once 'class/Registry.php';

$Registry = new Registry();

/*$Registry
	->link('Brain', 'Watch')
;*/

while(true) {
	$Registry->tick();
	sleep(1);
}
?>