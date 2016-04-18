Given(/^I am logging in$/) do
<<<<<<< HEAD
	visit('http://localhost:80')
=======
	Capybara.reset_sessions!
	visit('https://localhost/CSCI-310-Group-L/www/login/')
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc

end

When(/^I enter wrong password$/) do
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('testWrong')
<<<<<<< HEAD
	page.execute_script("$('button.login-button').click()")
=======
	page.execute_script("$('#login-button').click()")
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
end

Then(/^I see a notification$/) do
	find('#error').value == "invalid login parameters"
end
