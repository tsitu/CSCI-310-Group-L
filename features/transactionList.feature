Feature: transaction list

Scenario: seeing transaction list
	Given I see transactions
	Then the size is greater than 0