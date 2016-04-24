<?php

require_once __DIR__ . '/../www/src/inc/queries.php';

class budgetTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;


	function __construct(){

	}

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}

	public function testAddBudget() {
			$before_rowNumber = getNumberOfRowsBudget();
			BudgetManager::addBudget(500, 12, 2500, 100); 
			$after_rowNumber = getNumberOfRowsBudget();
			$this->assertEquals($before_rowNumber+1, $after_rowNumber);


	}
	public function testDeleteBudget() {
			$before_rowNumber = getNumberOfRowsBudget();
			BudgetManager::deleteBudget(500); 
			$after_rowNumber = getNumberOfRowsBudget();
			$this->assertEquals($before_rowNumber-1, $after_rowNumber);

	}
	public function testGetBudgetByInfo(){

	}
	public function testGetBudgetsByUser(){

	}

}

?>
