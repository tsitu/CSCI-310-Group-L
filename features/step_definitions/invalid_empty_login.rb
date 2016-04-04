Given(/^I am logging in empty$/) do
	visit('https://localhost/CSCI-310-Group-L/www/login/')

end

When(/^I enter empty username and password$/) do
	find('#login-username').set('')
	find('#login-password').set('')
	page.execute_script("$('button.login-button').click()")
end

Then(/^I see an empty notification$/) do
	find('#error').value == "Empty username or password"
end