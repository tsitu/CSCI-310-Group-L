<?php

#protected $backupGlobals = FALSE;

require_once __DIR__ . '/../www/src/inc/queries.php';

#require_once __DIR__ . '/../www/src/model/Transaction.php';
#require_once __DIR__ . '/../www/src/model/Account.php';
class MoneyTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = FALSE;

    //Account
    //function __construct($_id, $_institution, $_type)
    //Transaction
    //function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp)


  	public function testInsertTransaction(){
  		$num_row = getNumberOfRowsTransactions();
		insertTransaction(200, 100, "insertTest", 3000.00, "loan", 0);
		$expect_num = getNumberOfRowsTransactions();
        $num_row = $num_row+1;
		$this->assertEquals($num_row, $expect_num);

  	}
  	//test if the functino can remove 
  	//test if we delete all transaction, it return array with size of zero
    public function testRemoveAccountTransactions() {
    	//getting accountID
    	$num_row = getNumberOfRowsTransactions();
    	$firstAccountID = getAccountId("Bank of Test", "loan");
    	//getting Account regarding accountID

    	removeAccount(200,100);
		$expect_num = getNumberOfRowsTransactions();

        $num_row--;
    	$this->assertEquals($num_row, $expect_num);

    }

    //what if there is not id that we can delete
    public function testRemoveFromNothing() {

    	//check it there is string return is equal
    	$num_row = getNumberOfRowsTransactions();
    	$firstAccountID = getAccountId("test1-1", "test1-2");
 
    	removeAccount(500,$firstAccountID);
		$expect_row = getNumberOfRowsTransactions();
    	$this->assertEquals($num_row, $expect_row);

    }
    //GetAccount Test
    public function testGetAccount() {
    	//get an account object from an accountId
        $accountId = 2;

    	$newAccount = getAccount($accountId);

    	$newID = $newAccount->id;

        $newInstitution = $newAccount->institution;
        
        $newType = $newAccount->type;
    	//check if we can get the account as expected.
    	$this->assertEquals($newID, 2);
        $this->assertEquals($newInstitution, "test1-1");
        $this->assertEquals($newType, "test1-2");
    }

    public function testGetAccountID() {
        $institution = "test1-1";
        $type = "test1-2";

        $id = getAccountID($institution, $type);

        $this->assertEquals($id, 2);

    }

    public function testGetInvalidAccountID(){
        global $mysqli;

        $institution = "Bank of ";
        $type = "loan";
        if(is_null(getNumberOfRowsAccounts()))
            $initial_row_count = 0;
        else
            $initial_row_count = getNumberOfRowsAccounts();

        $id = getAccountID($institution, $type);


        if(is_null(getNumberOfRowsAccounts()))
            $final_row_count = 0;
        else
            $final_row_count = getNumberOfRowsAccounts();

        $initial_row_count = $initial_row_count+1;

        
        if( ($stmt = $mysqli->prepare("DELETE FROM accounts WHERE institution=? AND type=?") )) {

        //bind
        if(! $stmt->bind_param("ss", $institution, $type) )
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

        //execute
        if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

        //fetch result set
        $stmt->close();


        } else {
            echo "getAccount():Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
        }

        $this->assertEquals($initial_row_count, $final_row_count);


    }

    public function testGetAccountIDs() {
            
        $test_array = getAccountIds(2);
        $size_array = sizeof($test_array);

        $this->assertEquals($size_array,1);


    }

    public function testGetTransaction() {

        $test_array = getTransactions(2, 36);
        $size_array = sizeof($test_array);
        $descriptor_test = $test_array[0]->descriptor;
        $this->assertEquals($size_array, 1);

        
        $this->assertEquals($descriptor_test, 'transaction get');


    }

    public function testLoginValid() {

        $test_id = login("phpunit@gmail.com", "phpunit");
        $this->assertEquals($test_id, 2);
    }

    public function testLoginInvalid() {

       $test_id = login("invalid@gmail.com", "null");
       $this->assertEquals($test_id, null);


    }
}


?>
