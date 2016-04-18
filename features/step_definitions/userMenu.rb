Given(/^I am on the main UI$/) do
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
end

Then(/^I can see a user menu$/) do
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

Then(/^I should see a user menu$/) do
	page.has_content?('user-menu')
	expect(page).to have_selector('#user-menu', visible: true)
end 
