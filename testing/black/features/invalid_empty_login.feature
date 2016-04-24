Feature: login empty info

Scenario: empty username and password input
	Given I am logging in empty
	When I enter empty username and password
	Then I see an empty notification