Given(/^user is on the main UI$/) do
<<<<<<< HEAD
	visit("http://localhost:80")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
end

Then(/^user should see a list$/) do
=======
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

Then(/^user should see a list$/) do
	expect(current_path).to eq '/CSCI-310-Group-L/www/'
>>>>>>> eaf9b4e620b665761a05e90f3e05555f3efa2edc
	expect(page).to have_selector('#account-module')
end
