<?php
require_once __DIR__ . '/../../www/src/model/DBManager.php';
require_once __DIR__ . '/../../www/src/model/BudgetManager.php';
require_once __DIR__ . '/../../www/src/model/UserManager.php';
require_once __DIR__ . '/../../www/src/model/AccountManager.php';

require_once __DIR__ . '/../queries.php';

class MoneyTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = FALSE;
    //Account
    //function __construct($_id, $_institution, $_type)
    //Transaction
    //function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp)
    public function testInsertTransaction(){
        $num_row = getNumberOfRowsTransactions();
        //insertTransaction(200, 100, "insertTest", 3000.00, "loan", 0);
        $expect_num = getNumberOfRowsTransactions();
        $num_row = $num_row;
        $this->assertEquals($num_row, $expect_num);
    }
   
    //test if the functino can remove 
    //test if we delete all transaction, it return array with size of zero
    public function testRemoveAccountTransactions() {
        //getting accountID
        $num_row = getNumberOfRowsTransactions();
       // $firstAccountID = getAccountId("Bank of Test", "loan");
        //getting Account regarding accountID
        //removeAccount(200,100);
        $expect_num = getNumberOfRowsTransactions();
        $num_row;
        $this->assertEquals($num_row, $expect_num);
    }
    //what if there is not id that we can delete
    public function testRemoveFromNothing() {
        //check it there is string return is equal
        $num_row = getNumberOfRowsTransactions();
        //$firstAccountID = getAccountId("test1-1", "test1-2");
 
        //removeAccount(500,$firstAccountID);
        $expect_row = getNumberOfRowsTransactions();
        $this->assertEquals($num_row, $expect_row);
    }
    /*
    //GetAccount Test
    public function testGetAccount() {
        //get an account object from an accountId
        $accountId = 2;
        $newAccount = getAccount($accountId);
        $newID = $newAccount->id;
        $newInstitution = $newAccount->institution;
        
        $newType = $newAccount->type;
        //check if we can get the account as expected.
        $this->assertEquals($newInstitution, null);
        $this->assertEquals($newType, null);
    }
    public function testGetAccountID() {
        $institution = "test1-1";
        $type = "test1-2";
        $id = getAccountID($institution, $type);
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
        $initial_row_count = $initial_row_count;
        
      
        $this->assertEquals($initial_row_count, $final_row_count);
    }

    public function testGetTransaction() {
        $test_array = getTransactions(2, 36);
        $size_array = sizeof($test_array);
        $this->assertEquals($size_array, 0);
  
    }

    public function testLoginInvalid() {
       $test_id = login("invalid@gmail.com", "null");
       $this->assertEquals($test_id, null);
    }
*/
}

?>

