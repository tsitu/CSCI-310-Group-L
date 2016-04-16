<?php


class Account {
	/*private $id;
	private $institution;
	private $type;*/
	public $id;
	public $institution;
	public $type;


	function __construct($_id, $_institution, $_type) {
		$this->id = $_id;
		$this->institution = $_institution;
		$this->type = $_type;
	}
	function getID() {
		return $this->id;
	}
	function getInstitution(){
		return $this->institution;
	}
	function getType() {
		return $this->type;
	}

}

?>