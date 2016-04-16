Feature: logout button test

Scenario: logout button is clicked
	Given I am about to log out
	When I click on the log out button
	Then the page goes back to login page