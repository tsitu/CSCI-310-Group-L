<?php

require_once __DIR__ . '/../www/src/inc/queries.php';

class FAtest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;


//case 1; when empty csv file 
	public function parsingEmpty(){
		$filePath = '/var/www/html/CSCI-310-Group-L/test.csv'
		uploadCSV($filePath);

	
	}
	public function parsingWrongFormat() {

	}
	public function par() {

	}
}

?> 
