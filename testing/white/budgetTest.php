<?php

require_once __DIR__ . '/../../www/src/model/DBManager.php';
require_once __DIR__ . '/../../www/src/model/BudgetManager.php';

class budgetTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;
	protected $return_id;
	protected $return_id2;
	private $b;

	function __construct(){
	$this->b = BudgetManager::getInstance();

	}

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}

	public function testAddBudget() {

		
		$before_array = DBManager::getNumberOfRowsBudget();
		$before_rowNumber = count($before_array);
		if($before_rowNumber == false) 
			$before_rowNumber = 0;

		$return_id = (int)$this->b->addBudget(500, "loan", 12, 2500, 100); 
		
		$after_array = DBManager::getNumberOfRowsBudget();
		$after_rowNumber = count($after_array);

		$this->assertNotEquals($return_id, 0);
		$this->assertEquals($before_rowNumber+1, $after_rowNumber);

	}


	public function testGetBudgetByInfo(){
		$return_budget = $this->b->getBudgetByInfo(500, 12, 2500, "loan");
		$this->assertNotEquals($return_budget, null);
	}

	public function testGetBudgetsByUser(){
		$return_budgetArray = $this->b->getBudgetsByUser(500);
		$this->assertEquals(sizeof($return_budgetArray), 1);
	}

	public function testExistId() {

		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$return_id2 = $this->b->addBudget(500, "loan", 12, 2500,  100);
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($return_id2, 0);
		$this->assertEquals($before_rowNumber, $after_rowNumber);

	}

	public function testDeleteBudget() {
		$budget = $this->b->getBudgetByInfo(500,12,2500, "loan");
		$id = $budget["loan"]->id;


		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->b->deleteBudget($id); 
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($before_rowNumber-1, $after_rowNumber);

	}

	public function testDeleteInvalidBudget(){
		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->b->deleteBudget(768901); 
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($before_rowNumber, $after_rowNumber);

	}


	public function testGetInvalidBudgetByInfo(){
		$return_budget = $this->b->getBudgetByInfo(520,12,2500,"loan");
		$this->assertEquals(count($return_budget), 0);
	}

	public function testGetBudgetsByInvalidUser(){
		$return_budgetArray = $this->b->getBudgetsByUser(500);
		$this->assertEquals(sizeof($return_budgetArray), 0);
	}
	public function testGetAssetHistory(){
		//account_type = category
		//
		//$return_array = UserManager::getAssetHistory("asset", 2, );

	}
}

?>
