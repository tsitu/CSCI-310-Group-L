Given(/^I am about to log out$/) do
	visit('https://localhost/CSCI-310-Group-L/www/login/')
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
end

When(/^I click on the log out button$/) do
	page.execute_script("$('button.logout').click()")
end

Then(/^the page goes back to login page$/) do
	expect(current_path).to eq '/CSCI-310-Group-L/www/login/' 
end