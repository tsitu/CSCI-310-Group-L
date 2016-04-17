Feature: login incorrect password

Scenario: wrong password entered
	Given I am logging in
	When I enter wrong password
	Then I see a notification