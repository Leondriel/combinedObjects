<?php
class Element_Brain extends Element {

	public function ask($need) {
		switch($need) {
		case 'time':
			return date('H:i:s');
		}
	}

	protected function evaluate($need, $neighbour, $answer) {
		if(!isset($this->reliability[$need])) {
			$this->reliability[$need] = array();
		}
		$this->reliability[$need][$neighbour] = 0;
	}
}
?>