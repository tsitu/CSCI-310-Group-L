Given(/^user is on the main UI$/) do
	visit("https://localhost/CSCI-310-Group-L/www/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('button.login-button').click()")
	expect(current_path).to eq '/CSCI-310-Group-L/www/index.php'
end

Then(/^user should see a list$/) do
	expect(page).to have_selector('#account-module')
end
