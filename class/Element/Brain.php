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
	
	protected function evaluateTime($answer) {
		if(preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $answer)) {
			return 100;
		} elseif(preg_match('/[0-9]{2}:[0-9]{2}/', $answer)) {
			return 50;
		} elseif(preg_match('/[0-9]{2}', $answer)) {
			return 10;
		} else {
			return 0;
		}
	}
}
?>