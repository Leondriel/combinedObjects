<?php
class Element_Watch extends Element {

	public function ask($need) {
		switch($need) {
		case 'time':
			return date('H');
		}
	}
	
	protected function init() {
		
	}
}
?>