Feature: transaction list

Scenario: seeing transaction list
	Given I see transactions
	When I click on button
	Then the size decreases