Given(/^I am logging in$/) do
	visit('http://localhost:80')

end

When(/^I enter wrong password$/) do
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('testWrong')
	page.execute_script("$('button.login-button').click()")
end

Then(/^I see a notification$/) do
	find('#error').value == "invalid login parameters"
end
