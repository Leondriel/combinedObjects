<?php
class Need {
    protected $name = "";
	protected $prio = 0;
	protected $urgency = 0;
	protected $riseUrgency = 1;
	
	function __construct($prio = 0, $riseUrgency = 1) {
		$this->name = str_replace('Need_', '', get_class($this));
		$this->prio = $prio;
		$this->riseUrgency = $riseUrgency;
	}
	
	public function getName() {
		return $this->name;
	}

	public function getPrio() {
		return $this->prio;
	}

	public function getUrgency() {
		return $this->urgency;
	}

	public function getRiseUrgency() {
		return $this->riseUrgency;
	}
	
	public function incrementUrgency() {
		$this->urgency+= $riseUrgency;
		return $this;
	}
}
?>