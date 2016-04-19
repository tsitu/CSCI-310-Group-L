Feature: User adds account

Scenario: User adds an account
	Given user has an account csv file
	When user adds it through browse
	Then account list increases
