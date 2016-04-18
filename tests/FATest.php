<?php

require_once __DIR__ . '/../www/src/inc/queries.php';
require_once __DIR__ . '/../www/src/model/AccountManager.php';

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
	
	}
	
	public function testParsingCorrectFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/CFTest.csv';
		$returnArray = uploadCSV($filePath, "testing");
		$arraySize = sizeof($returnArray);
	
		$this->assertEquals($arraySize , 3);

	}
	
	public function testIncorrectData(){
		$filePath = '/var/www/html/CSCI-310-Group-L/incorrectData.csv';
		$type = uploadCSV($filePath, "testing");
		$this->assertEquals($type, null);
	}
	public function testIncorrectFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/wrongFormat.txt';
		$type = uploadCSV($filePath, "testing");
		$this->assertEquals($type, null);
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
		echo "\r\n".addslashes($info->institution)."\r\n"."PHPTest Bank\r\n";
		$this->assertEquals($info->institution, "PHPTest Bank\0\0\0\0");
		$this->assertEquals($info->type,"Credit Card\0\0\0\0\0");
		$this->assertEquals($info->user_id,500);

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

	}

}

?> 
