<?php

require_once __DIR__ . '/../www/src/inc/queries.php';

class FATest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}


//case 1; when empty csv file 
	public function testParsingEmpty(){
		$filePath = '/var/www/html/CSCI-310-Group-L/empty.csv';
		$returnValue = uploadCSV($filePath);
		$this->assertEquals($returnValue,0);
		//check if size of the data table has been changed.
	
	}
	public function testParsingCorretFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/CFTest.csv';
		$returnArray = uploadCSV($filePath);
		$arraySize = sizeof($returnArray);
		print $arraySize;
		$this->assertEquals($arraySize , 4);

	}
	public function testData(){

	}
	public function testIncorrectFormat() {

	}
	public function testIncorrectType() {

	}
	public function testAddAccount(){
		//institution/type/userid
		//BankofAmerica/creditcard/userid
		$ADBManager = new AccountDBManager();
		$ADBManager->addAccount("PHPTest Bank", "Credit Card", 500);
		//check the size of db table 
		
		//it returns account object
		$info = $ADBManager->getAccountByInfo("PHPTest Bank", "Credit Card", 1);
		$this->assertEquals($info->institution,"PHPTest Bank");
		$this->assertEquals($info->type,"Credit Card");
		$this->assertEquals($info->user_id,500)

	} 
	public function testDeleteAccount() {

		$ADBManager = new AccountDBManager();
		$info = $ADBManager->getAccountByInfo("PHPTest Bank", "Credit Card", 500);
		$ADBManager->deleteAccount($info->id);
		//check the size of database to confirm it is 

		//we can use getaccountByInfo
		//if it returns null, it is working
		$info = $ADBManager->getAccountByInfo("PHPTest Bank", "Credit Card", 1);
		$this->asserEquals($info, null);

	}
	public function 

}

?> 
