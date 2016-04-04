

<?php
require_once require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/db/queries.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Transaction.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/CSCI-310-Group-L/data/Account.php";
class MoneyTest extends PHPUnit_Framework_TestCase
{
    //Account
    //function __construct($_id, $_institution, $_type)
    //Transaction
    //function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp)


    //test if the functino can remove
  	//test if we delete all transaction, it return array with size of zero

    public function testRemove() {
    	//getting accountID
    	insertTransaction(1, "test1-1", "test1-2", 3000.00, "test1-3", 0);
    	$firstAccountID = getAccountId("test1-1", "test1-2");
    	//getting Account regarding accountID
    	$account = getAccount($firstAccountID);

    	removeAccount(1,$firstAccount);
    	$transactionArray = getTransactions($account, $firstAccount);
    	$num_transaction = sizeOf($transactionArray);
    	//have to check if there is no transaction regarding the id and accountID
    	$this->assertEqual($num_transaction, 0);

    }
    //what if there is not id that we can delete
    public function testRemoveFromNothing() {
    	//check it there is string return is equal
    	$firstAccountID = getAccountId("Bank of America", "Students Loan");
    	//getting Account regarding accountID
    	$account = getAccount($firstAccountID);
    	removeAccount($account,$firstAccount);
    	$transactionArray = getTransactions($account, $firstAccount);

    	//have to check if there is no transaction regarding the id and accountID
    	$this->expectOutputString('errorstatement');//todo list
    }
    //GetAccount Test
    public function GetAccountTest() {
    	global $mysqli;
    	//get an account object from an accountId
    	$newAccount = getAccount($accountId);
    	$newID = $newAccount->getID();
    	//check if we can get the account as expected.
    	$this->assertEqual($newID, "id");//we need to fill this out.
    }

    public function insertTransactionTest() {

    	$userID = 'testID';
    	$accountID = 'testAccountID';
    	$descriptor = 'test';
    	$amount = 123;
    	$category = "test";
    	$timestamp = 123;

    	insertTransaction($userId, $accountId, $descriptor, $amount, $category, $timestamp);

    	$returnArray = getTransactions($userID, $accountID);
    	$sizeOfArray = sizeof($returnArray);

    	$this->assertEqual($sizeOfArray, 0);

    }

}


?>
