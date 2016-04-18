<?php

require_once __DIR__ . '/../www/src/inc/queries.php';

class SecureDataTest extends PHPUnit_Framework_TestCase
{

	protected $backupGlobals = FALSE;
	protected $encrypted;
	protected $decrypted;
	protected $DB;

	protected function __construct(){
		$DB = new DBManager();
	}


	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '.../www';
	}

	public function testEncrypt(){\

		$encrypted = $DB->encrypt("Testing");
		$this->assertNotEquals($encrypted, "Testing");

		
	}
	public function testDecrypt(){

		$decrypted = $DB->decrypt($encrypted);
		$this->asserNotEquals($encrypted, $decrypted);


	}

}

?>
