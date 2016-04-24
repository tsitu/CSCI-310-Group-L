Feature: User delete account

Scenario: user deletes an account
	Given user has an account
	When user clicks on the delete button
	Then account list shortens
	
