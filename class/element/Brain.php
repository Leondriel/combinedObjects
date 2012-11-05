<?php
class Element_Brain {
	const NOT_OFFERING = "I can't help you!";
	
	protected $needs = array(
		'time' => array(
			'chance' => 10,
			'prio' => 100
		)
	);
	
	protected $offers = array(
		
	);
	
	protected $neighbours = array(
		'Watch'
	);
	
	protected $reliability = array(
		
	);
	
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
	}
	
	protected function say($text) {
		echo $text;
	}
	
	protected function evaluate($need, $neighbour, $answer) {
		if(!isset($this->reliability[$need])) {
			$this->reliability[$need] = array();
		}
		if($answer == self::NOT_OFFERING) {
			$this->reliability[$need][$neighbour] = 0;
		} else {
			if(preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/')) {
				$this->reliability[$need][$neighbour] = 100;
				$this->say("Thank you very much!");
			} elseif(preg_match('/[0-9]{2}:[0-9]{2}/')) {
				$this->reliability[$need][$neighbour] = 50;
				
			} else {
				$this->reliability[$need][$neighbour] = 0;
				$this->say("I don't understand");
			}
		}
	}
}
?>