Given(/^user is on the main UI$/) do
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
end

Then(/^user should see a list$/) do
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

Then(/^user should see a list$/) do
	expect(current_path).to eq '/CSCI-310-Group-L/www/'
	expect(page).to have_selector('#account-module')
end
