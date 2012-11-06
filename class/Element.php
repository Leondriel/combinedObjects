<?php
abstract class Element {
    const NOT_OFFERING = "I can't help you!";
	
	protected $needs = array();
	protected $offers = array();
	protected $neighbours = array();
	protected $reliability = array();
	protected $Registry;
	
	protected function say($text) {
		echo get_class($this) . ': ' . $text . "\n";
	}
	
	public function __construct($Registry) {
		$this->Registry = $Registry;
	}
	
	public function tick() {
		$rand = rand(1,100);
		foreach($this->needs as $need => &$needInfo) {
			$needInfo['urgency']+= $needInfo['riseUrgency'];
			if($needInfo['urgency'] >= $rand) {
				$this->say('Asking for ' . $need);
				foreach($this->neighbours as $neighbour) {
					if(isset($this->reliability[$need]) && isset($this->reliability[$need][$neighbour])) {
						if($this->reliability[$need][$neighbour] < $need['prio']) {
							if($this->reliability[$need][$neighbour] < ($need['prio'] - $need['urgency'])) {
								continue;
							} else {
								$this->say('I need ' . $need . 'so much that I even ask ' . $neighbour);
							}
						}
					}					
					$answer = $this->Registry->getInstance($neighbour)->ask($need);
					$this->say($neighbour . ' told me ' . $answer);
					if($answer == self::NOT_OFFERING) {
						if(!isset($this->reliability[$need])) {
							$this->reliability[$need] = array();
						}
						$this->reliability[$need][$neighbour] = 0;
					} elseif($this->evaluate($need, $neighbour, $answer) >= $needInfo['prio']) {
						$this->say("Thanks!");
						break 1;
					} else {
						$this->say("You didn't help");
					}
				}
			}
		}
		return $this;
	}
	
	public function addNeighbour($neighbour) {
		$this->neighbours[] = $neighbour;
		return $this;
	}
	
	abstract public function ask($need);
	
	abstract protected function evaluate($need, $neighbour, $answer);
}
?>