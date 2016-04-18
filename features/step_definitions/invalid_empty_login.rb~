Given(/^I am logging in empty$/) do
<<<<<<< HEAD
	visit('http://localhost:80')

end

When(/^I enter empty username and password/) do
	find('#login-username').set('')
	find('#login-password').set('')
	page.execute_script("$('button.login-button').click()")
=======
	Capybara.reset_sessions!
	visit('https://localhost/CSCI-310-Group-L/www/login/')

end

When(/^I enter empty username and password$/) do
	find('#login-username').set('')
	find('#login-password').set('')
	page.execute_script("$('#login-button').click()")
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end

Then(/^I see an empty notification$/) do
	find('#error').value == "Empty username or password"
end