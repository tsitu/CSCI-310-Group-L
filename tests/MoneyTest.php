

<?php
class MoneyTest extends PHPUnit_Framework_TestCase
{
    //Account
    //function __construct($_id, $_institution, $_type)
    //Transaction
    //function __construct($_id, $_userId, $_accountId, $_descriptor, $_amount, $_category, $_timestamp)

    public function testCanBeNegated() {
        // Arrange
        $a = new Money(1);

        // Act
        $b = $a->negate();

        // Assert
        $this->assertEquals(-1, $b->getAmount());
    }
    //Remove account test
    //Getaccount test
    //GetAccountID test
    //getAccount test
    //inserTransaction test
    //getTransactions test


    //test if the functino can remove 
    public function testRemove() {
    	$firstAccount = getAccountID("Bank of America", "Students Loan");
    	$secondAccount = getAccountID("Wells Fargo", "Debit Card");
    	

    }
    //what if there is not id that we can delete
    public function testRemoveFromNothing() {

    }
    //

}


?>
