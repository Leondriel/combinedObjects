<?php
class Element_Watch extends Element {

	public function answerTime() {
		return date('H:i:s');
	}
	
	protected function init() {
		$this->setDefault(self::KEY_NEIGHBOURS, array(
			'Brain'
		));
	}
}
?>