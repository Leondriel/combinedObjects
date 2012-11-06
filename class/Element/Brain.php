<?php
class Element_Brain extends Element {
	
	protected $needs = array(
		'time' => array(
			'prio' => 100,
			'urgency' => 0,
			'riseUrgency' => 5
		)
	);

	protected $neighbours = array(
		'Watch'
	);
	
	public function ask($need) {
		return self::NOT_OFFERING;
	}
	
	protected function evaluate($need, $neighbour, $answer) {
		if(!isset($this->reliability[$need])) {
			$this->reliability[$need] = array();
		}
		if($answer == self::NOT_OFFERING) {
			$this->reliability[$need][$neighbour] = 0;
		} else {
			switch($need) {
			case 'time':
				$this->evaluateTime($need, $neighbour, $answer);
				break 1;
			default:
				$this->reliability[$need][$neighbour] = 0;
			}
		}
		return $this->reliability[$need][$neighbour];
	}
	
	protected function evaluateTime($need, $neighbour, $answer) {
		if(preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $answer)) {
			$this->reliability[$need][$neighbour] = 100;
		} elseif(preg_match('/[0-9]{2}:[0-9]{2}/', $answer)) {
			$this->reliability[$need][$neighbour] = 50;

		} else {
			$this->reliability[$need][$neighbour] = 0;
		}
		return $this;
	}
}
?>