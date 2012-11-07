<?php
class Element_Brain extends Element {
	
	public function ask($need) {
		return self::NOT_OFFERING;
	}
	
	protected function init() {
		$this->setDefault(self::KEY_NEEDS, array(
			new Need_Time(50, 10),
			new Need_Light(10, 0.5)
		));
		
		$this->setDefault(self::KEY_NEIGHBOURS, array(
			'Watch',
			'Sun'
		));
	}
	
	protected function evaluateLight($answer) {
		if($answer == 'Sunlight') {
			return 75;
		} else {
			return 0;
		}
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