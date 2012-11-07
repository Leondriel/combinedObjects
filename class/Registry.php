<?php
function __autoload($className) {
	require_once 'class/' . str_replace('_', '/', $className) . '.php';
}

class Registry {
	protected $Elements = array();
	protected $Cache = NULL;
	
	public function __construct() {
		$this->Cache = new Memcached();
		$this->Cache->addServer('localhost', 11211);
		$this->Elements = array(
			'Brain' => new Element_Brain($this),
			'Watch' => new Element_Watch($this)
		);
	}
	
	public function getInstance($class) {
		return $this->Elements[$class];
	}
	
	public function getCache() {
		return $this->Cache;
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