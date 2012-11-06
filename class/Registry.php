<?php
require_once 'class/Element.php';
require_once 'class/Element/Brain.php';
require_once 'class/Element/Watch.php';

class Registry {
	protected $Elements = array();
	
	public function __construct() {
		$this->Elements = array(
			'Brain' => new Brain,
			'Watch' => new Watch
		);
	}
	
	public function getInstance($class) {
		return $this->Elements[$class];
	}
	
	public function tick() {
		foreach($this->Elements as $Element) {
			$Element->tick();
		}
	}
	
	public function link($elementA, $elementB) {
		$this->getInstance($elementA)->addNeighbour($elementB);
		$this->getInstance($elementB)->addNeighbour($elementA);
		return $this;
	}
}
?>