<?php
abstract class Element {
    const NOT_OFFERING = "I can't help you!";
	const KEY_NEEDS = 0;
	const KEY_OFFERS = 1;
	const KEY_NEIGHBOURS = 2;
	const KEY_RELIABILITY = 3;
	
	protected $name = "";
	protected $default = array(
		self::KEY_NEEDS => array(),
		self::KEY_OFFERS => array(),
		self::KEY_NEIGHBOURS => array(),
		self::KEY_RELIABILITY => array()
	);
	protected $wording = array(
		self::KEY_NEEDS => 'needs',
		self::KEY_OFFERS => 'offers',
		self::KEY_NEIGHBOURS => 'neighbours',
		self::KEY_RELIABILITY => 'reliability'
	);
	protected $Registry;
	
	protected function say($text) {
		echo get_class($this) . ': ' . $text . "\n";
	}
	
	public function __construct($Registry) {
		$this->Registry = $Registry;
		$this->name = str_replace('Element_', '', get_class($this));
		$this->init();
	}
	
	abstract protected function init();
	
	protected function setDefault($key, $value) {
		$this->default[$key] = $value;
		return $this;
	}
	
	protected function getCache() {
		return $this->Registry->getCache();
	}
	
	protected function set($key, $value) {
		$this->getCache()->set($this->name . '_' . $key, $value);
		return $this;
	}
	
	protected function get($key) {
		$result = $this->getCache()->get($this->name . '_' . $key);
		if($this->getCache()->getResultCode() == Memcached::RES_NOTFOUND) {
			$this->say('I forgot my ' . $this->wording[$key]);
			$result = $this->default[$key];
		}
		return $result;
	}
	
	public function tick() {
		$rand = rand(1,100);
		$Needs = $this->get(self::KEY_NEEDS);
		$offers = $this->get(self::KEY_OFFERS);
		$neighbours = $this->get(self::KEY_NEIGHBOURS);
		$reliability = $this->get(self::KEY_RELIABILITY);
		
		foreach($Needs as $Need) {
			$Need->incrementUrgency();
			if($Need->getUrgency() >= $rand) {
				$this->say('Asking for ' . $Need->getName());
				foreach($neighbours as $neighbour) {
					if(isset($reliability[$Need->getName()]) && isset($reliability[$Need->getName()][$neighbour])) {
						if($reliability[$Need->getName()][$neighbour] < $Need->getPrio()) {
							if($reliability[$Need->getName()][$neighbour] < ($Need->getPrio() - $Need->getUrgency())) {
								continue;
							} else {
								$this->say('I need ' . $Need->getName() . 'so much that I even ask ' . $neighbour);
							}
						}
					}					
					$answer = $this->Registry->getInstance($neighbour)->ask($Need->getName());
					$this->say($neighbour . ' told me ' . $answer);
					if($answer == self::NOT_OFFERING) {
						if(!isset($reliability[$Need->getName()])) {
							$reliability[$need] = array();
						}
						$reliability[$Need->getName()][$neighbour] = 0;
					} elseif($this->evaluate($Need->getName(), $neighbour, $answer, $reliability) >= $Need->getPrio()) {
						$this->say("Thanks!");
						$Need->resetName();
						break 1;
					} else {
						$this->say("You didn't help");
					}
				}
			}
		}
		
		$this
			->set(self::KEY_NEEDS, $Needs)
			->set(self::KEY_OFFERS, $offers)
			->set(self::KEY_NEIGHBOURS, $neighbours)
			->set(self::KEY_RELIABILITY, $reliability)
		;
		return $this;
	}
	
	public function addNeighbour($neighbour) {
		$neighbours = $this->get(self::KEY_NEIGHBOURS);
		$neighbours[] = $neighbour;
		$this->set(self::KEY_NEIGHBOURS, $neighbours);
		return $this;
	}
	
	public function ask($need) {
		if(method_exists($this, 'answer' . $need)) {
			return call_user_func(array($this, 'answer' . $need));
		} else {
			return self::NOT_OFFERING;
		}
	}
	
	protected function evaluate($need, $neighbour, $answer, &$reliability) {
		if(!isset($reliability[$need])) {
			$reliability[$need] = array();
		}
		if(!method_exists($this, 'evaluate' . $need)) {
			$reliability[$need][$neighbour] = 0;
		} else {
			$reliability[$need][$neighbour] = call_user_func(array($this, 'evaluate' . $need), $answer);
		}
		return $reliability[$need][$neighbour];
	}
}