<?php


require_once __DIR__ . '/../../www/src/model/DBManager.php';
require_once __DIR__ . '/../../www/src/model/BudgetManager.php';
require_once __DIR__ . '/../../www/src/model/UserManager.php';
class FATest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}


//case 1; when empty csv file 
	public function testParsingEmpty(){
		$filePath = '/var/www/html/CSCI-310-Group-L/empty.csv';
		$returnValue = uploadCSV($filePath, "testing");
		$this->assertEquals($returnValue,0);
		//check if size of the data table has been changed.
		echo "\r\nParsing Empty file Testing done\r\n";
	
	}
	
	public function testParsingCorrectFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/CFTest.csv';
		$returnArray = uploadCSV($filePath, "testing");
		$arraySize = sizeof($returnArray);
	
		$this->assertEquals($arraySize , 3);
		echo "\r\nParsing Correct Format Testing done\r\n";

	}
	
	public function testIncorrectData(){
		$filePath = '/var/www/html/CSCI-310-Group-L/incorrectData.csv';
		$type = uploadCSV($filePath, "testing");
		$this->assertEquals($type, null);
		echo "\r\nTest incorrect dataTesting done\r\n";
	}
	public function testIncorrectFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/wrongFormat.txt';
		$type = uploadCSV($filePath, "testing");
		$this->assertEquals($type, null);
		echo "\r\nIncorrect format Testing done\r\n";
	}
	public function testAddAccount(){

		//institution/type/userid
		//BankofAmerica/creditcard/userid
		$ADBManager = AccountManager::getInstance();

		$before = getNumberOfRowsAccounts();
		$ADBManager->addAccount("PHPTest Bank", "Credit Card", 500);
		//check the size of db table 
		$after = getNumberOfRowsAccounts();

		//it returns account object
		$info = $ADBManager->getAccountByInfo(500, "PHPTest Bank", "Credit Card");


		$this->assertEquals($before+1,$after);
		$this->assertEquals($info->institution, "PHPTest Bank\0\0\0\0");
		$this->assertEquals($info->type,"Credit Card\0\0\0\0\0");
		$this->assertEquals($info->user_id,500);
		echo "\r\nAdd account Testing done\r\n";

	} 
	public function testDeleteAccount() {

		
		$ADBManager = AccountManager::getInstance();
		$before = getNumberOfRowsAccounts();
		$info = $ADBManager->getAccountByInfo(500, "PHPTest Bank", "Credit Card");
		$ADBManager->deleteAccount($info->id);
		$after = getNumberOfRowsAccounts();
		//check the size of database to confirm it is 

		//we can use getaccountByInfo
		//if it returns null, it is working
		$info = $ADBManager->getAccountByInfo("PHPTest Bank", "Credit Card", 1);
		$this->assertEquals($before-1, $after);
		$this->assertEquals($info, null);
		echo "\r\nDelete account Testing done\r\n";
	}

}

?> 
