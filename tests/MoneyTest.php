

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/queries.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Transaction.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Account.php";
class MoneyTest extends PHPUnit_Framework_TestCase
{
    //Account
    //function __construct($_id, $_institution, $_type)
    //Transaction
    //function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp)


  	public function insertTransactionTest(){
  		$num_row = getNumberOfRows('transactions');
		insertTransaction(200, 100, "Student loan", 3000.00, "loan", 0);
		$expect_num = getNumberOfRows('transactions');

		$this->assertEqual($num_row+1, $expect_num);

  	}
  	//test if the functino can remove 
  	//test if we delete all transaction, it return array with size of zero
    public function testRemove() {
    	//getting accountID

    	$num_row = getNumberOfRows('transactions');
    	$firstAccountID = getAccountId("Bank of America", "Student Loan");
    	//getting Account regarding accountID

    	removeAccount(1,$firstAccount);
		$expect_num = getNumberOfRows('transactions');


    	$this->assertEqual($num_row-1, $expect_num);

    }
    //GetAccount Test
    public function GetAccountTest() {
    	//get an account object from an accountId
    	$newAccount = getAccount($accountId);
    	$newID = $newAccount->getID();
    	//check if we can get the account as expected.
    	$this->assertEqual($newID, "id");//we need to fill this out.
    }
    //
    public function GetAccountTest1() {

    }


    //what if there is not id that we can delete
    public function testRemoveFromNothing() {
    	//check it there is string return is equal
    	$num_row = getNumberOfRows('transactions');
    	$firstAccountID = getAccountId("test1-1", "test1-2");
 
    	removeAccount(500,$firstAccountID);
		$expect_row = getNumberOfRows('transactions');
    	$this->assertEqual($num_row, $expect_num);

    }



}


?>
