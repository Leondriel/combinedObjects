<?php
class Element_Watch extends Element {

	public function answerTime() {
		return date('H');
	}
	
	public function answerLight() {
		return 'Sunlight';
	}
	
	protected function init() {
		$this->setDefault(self::KEY_NEIGHBOURS, array(
			'Brain'
		));
	}
}
?>