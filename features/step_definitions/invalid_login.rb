Given(/^I am logging in$/) do
	Capybara.reset_sessions!
	visit('https://localhost/login/')

end

When(/^I enter wrong password$/) do
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('testWrong')
	page.execute_script("$('#login-button').click()")
end

Then(/^I see a notification$/) do
	find('#error').value == "invalid login parameters"
end
