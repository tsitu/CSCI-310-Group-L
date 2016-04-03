<?php


class Account {
	public $id;
	public $institution;
	public $type;

	function __construct($_id, $_institution, $_type) {
		$id = $_id;
		$institution = $_institution;
		$type = $_type;
	}


}
