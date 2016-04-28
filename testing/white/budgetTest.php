<?php

require_once __DIR__ . '/../../www/src/model/DBManager.php';
require_once __DIR__ . '/../../www/src/model/BudgetManager.php';
require_once __DIR__ . '/../../www/src/model/UserManager.php';


class budgetTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;
	protected $return_id;
	protected $return_id2;
	private $b;
	private $u;

	function __construct(){
	$this->b = BudgetManager::getInstance();
	$this->u = UserManager::getInstance();
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
		echo "Adding budget test done\n";

	}


	public function testGetBudgetByInfo(){
		$return_budget = $this->b->getBudgetByInfo(500, 12, 2500, "loan");
		$this->assertNotEquals($return_budget, null);
		echo "Get budget by info test done\n";
	}

	public function testGetBudgetsByUser(){
		$return_budgetArray = $this->b->getBudgetsByUser(500);
		$this->assertEquals(sizeof($return_budgetArray), 1);
		echo "Get budget by User done\n";
	}

	public function testExistId() {

		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$return_id2 = $this->b->addBudget(500, "loan", 12, 2500,  100);
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($return_id2, 0);
		$this->assertEquals($before_rowNumber, $after_rowNumber);
		echo "Test Exist Id in the database test done\n";

	}

	public function testDeleteBudget() {
		$budget = $this->b->getBudgetByInfo(500,12,2500, "loan");
		$id = $budget["loan"]->id;


		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->b->deleteBudget($id); 
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($before_rowNumber-1, $after_rowNumber);
		echo "Delete budget test done\n";
	}

	public function testDeleteInvalidBudget(){
		$before_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->b->deleteBudget(768901); 
		$after_rowNumber = count(DBManager::getNumberOfRowsBudget());
		$this->assertEquals($before_rowNumber, $after_rowNumber);
		echo "Delete invalid budget test done\n";

	}


	public function testGetInvalidBudgetByInfo(){
		$return_budget = $this->b->getBudgetByInfo(520,12,2500,"loan");
		$this->assertEquals(count($return_budget), 0);
		echo "get budget by invalid info test done\n";
	}

	public function testGetBudgetsByInvalidUser(){
		$return_budgetArray = $this->b->getBudgetsByUser(500);
		$this->assertEquals(sizeof($return_budgetArray), 0);
		echo "get budget by invalid user test done\n";
	}
	public function testGetAssetHistory(){
		//account_type = category
		$start_date = new DateTime('04/01/2011');
		$end_date = new DateTime('05/11/2016');
		$return_array = $this->u->getAssetHistory("asset", 2, $start_date, $end_date);
		$this->assertEquals(count($return_array), 10);
		
		echo "Get asset History test done\n";

	}
}

?>
