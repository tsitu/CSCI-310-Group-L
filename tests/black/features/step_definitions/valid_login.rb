Given(/^user is on the login page$/) do
	Capybara.reset_sessions!
	visit("https://localhost/CSCI-310-Group-L/www/login/")
end

When(/^user types the right password and username$/) do
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
end

Then(/^user can login$/)do
	expect(current_path).to eq '/CSCI-310-Group-L/www/'
end 
