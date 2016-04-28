<?php


require_once __DIR__ . '/../../www/src/model/DBManager.php';
require_once __DIR__ . '/../../www/src/model/BudgetManager.php';
require_once __DIR__ . '/../../www/src/model/UserManager.php';

class SecureDataTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;
	protected $encrypted;
	protected $decrypted;
	protected $DB;

	function __construct(){

	}


	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}

	public function testEncrypt(){
		$this->encrypted = DBManager::encrypt('Testing');
		$this->assertNotEquals($this->encrypted, "Testing");
		echo "\r\nEncrypt Testing done\r\n";

		
	}
	public function testDecrypt(){

		$decrypted = DBManager::decrypt($this->encrypted);
		$this->assertNotEquals($this->encrypted, $decrypted);
		echo "\r\nDecrypt Testing done\r\n";


	}

}

?>
