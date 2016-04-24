<?php

require_once __DIR__ . '/../../www/src/inc/queries.php';

class budgetTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;
	protected $return_id;
	protected $return_id2;


	function __construct(){

	}

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}

	public function testAddBudget() {
		$before_rowNumber = getNumberOfRowsBudget();
		$return_id = BudgetManager::addBudget(500, 12, 2500, 100); 
		$after_rowNumber = getNumberOfRowsBudget();
		$this->assertEquals($return_id, 500);
		$this->assertEquals($before_rowNumber+1, $after_rowNumber);

	}
	public function testExistId() {
		$before_rowNumber = getNumberOfRowsBudget();
		$return_id2 = BudgetManager::addBudget(500, 12, 2500, 100);
		$after_rowNumber = getNumberOfRowsBudget();
		$this->assertEquals($return_id2, null);
		$this->assertEquals($before_rowNumber, $after_rowNumber);

	}

	public function testDeleteBudget() {
		$before_rowNumber = getNumberOfRowsBudget();
		BudgetManager::deleteBudget(500); 
		$after_rowNumber = getNumberOfRowsBudget();
		$this->assertEquals($before_rowNumber-1, $after_rowNumber);

	}
	public function testGetBudgetByInfo(){
		$return_budget = BudgetManager::getBudgetByInfo(520,12,2500);
		$this->asserEquals($return_budget, null);
	}
	public function testGetBudgetsByUser(){
		$return_budgetArray = BudgetManager::getBudgetByUser(500);
		$this->assertEquals(sizeof($return_budgetArray), 1);
	}
	public function testEmptyBudget(){
		$return_budgetArray = BudgetManager::getBudgetByUser(505);
		$this->assertEquals(sizeof($return_budgetArray), 0);
	}

}

?>
