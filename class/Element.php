<?php
abstract class Element {
    const NOT_OFFERING = "I can't help you!";
	
	protected $needs = array();
	protected $offers = array();
	protected $neighbours = array();
	protected $reliability = array();
	
	protected function say($text) {
		echo get_class($this) . ': ' . $text . "\n";
	}
	
	public function tick() {
		$rand = rand(1,100);
		foreach($this->needs as $need => $needInfo) {
			if($needInfo['chance'] >= $rand) {
				$this->say('Asking for ' . $need);
				foreach($neighbours as $neighbour) {
					$answer = call_user_func(array($neighbour, 'ask'), $need);
					$this->say($neighbour . ' told me ' . $result);
					if($answer == self::NOT_OFFERING) {
						if(!isset($this->reliability[$need])) {
							$this->reliability[$need] = array();
						}
						$this->reliability[$need][$neighbour] = 0;
					} elseif($this->evaluate($need, $neighbour, $result) >= $needInfo['prio']) {
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