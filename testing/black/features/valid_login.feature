Feature: logging in

Scenario: log in function test
	Given user is on the login page
	When user types the right password and username
	Then user can login