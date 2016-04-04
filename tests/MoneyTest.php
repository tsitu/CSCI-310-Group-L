<?php
class MoneyTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testCanBeNegated() {
        // Arrange
        $a = new Money(1);

        // Act
        $b = $a->negate();

        // Assert
        $this->assertEquals(-1, $b->getAmount());
    }

    //test if the functino can remove 
    public function testRemove() {
    	$a = new Transaction(1,2,3,4,5,6,7);

    	removeAccount(1,2);

    	

    }
    //what if there is not id that we can delete
    public function testRemoveFromNothing() {

    }
    //

}


?>
