<?php

require_once __DIR__ . '/../www/src/inc/queries.php';

class FATest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;


//case 1; when empty csv file 
	public function testParsingEmpty(){
		$filePath = '/var/www/html/CSCI-310-Group-L/empty.csv';
		$returnValue = uploadCSV($filePath);
		$this->assertEquals($returnValue,0);
		//check if size of the data table has been changed.
	
	}
	public function testParsingWrongFormat() {
		$filePath = '/var/www/html/CSCI-310-Group-L/CFTest.csv';
		$returnArray = uploadCSV($filePath);
		$arraySize = sizeof($returnArray);
		print $arraySize;
		$this->assertEquals($arraySize , 4);

	}
	public function par() {

	}
}

?> 
